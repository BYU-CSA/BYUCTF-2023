<?php
	/**
	 * ChickenASM Programming Language
	 * Copyright (C) 2013 powder96 <https://github.com/powder96/>
	 *
	 * This program is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * This program is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
	 */
	
	namespace ChickenASMLanguage;
	
	define('ChickenASMLanguage\\OPCODE_EXIT'     , 0);
	define('ChickenASMLanguage\\OPCODE_CHICKEN'  , 1);
	define('ChickenASMLanguage\\OPCODE_ADD'      , 2);
	define('ChickenASMLanguage\\OPCODE_SUBTRACT' , 3);
	define('ChickenASMLanguage\\OPCODE_MULTIPLY' , 4);
	define('ChickenASMLanguage\\OPCODE_COMPARE'  , 5);
	define('ChickenASMLanguage\\OPCODE_LOAD'     , 6);
	define('ChickenASMLanguage\\OPCODE_STORE'    , 7);
	define('ChickenASMLanguage\\OPCODE_JUMP'     , 8);
	define('ChickenASMLanguage\\OPCODE_CHAR'     , 9);
	
	final class VirtualMachineException extends \RuntimeException {}
	
	final class VirtualMachine {
		private $stack;
		private $programCounter;
		private $executedOpcodes;
		private $executedOpcodesLimit;
		private $executionLog;
		
		public function getExecutionLog() {
			return $this->executionLog;
		}
		
		public function setExecutedOpcodesLimit($limit) {
			$this->executedOpcodesLimit = $limit;
		}
		
		public function __construct($opcodes, $input = '') {
			$this->stack = array();

			array_push($this->stack, $this->stack);
			array_push($this->stack, $input);

			foreach($opcodes as $opcode)
				array_push($this->stack, $opcode);

			array_push($this->stack, null);
			
			$this->programCounter = 2;
			
			$this->executedOpcodes = 0;
			$this->setExecutedOpcodesLimit(30000);
			
			$this->executionLog = '';
		}
		
		public function execute() {
			while($this->programCounter < count($this->stack)) {
				if($this->executedOpcodes++ > $this->executedOpcodesLimit)
					throw new VirtualMachineException('Maximum number of executed opcodes is exceeded');
				
				$opcode = $this->stack[$this->programCounter];
				
				$this->executionLog .= str_pad($this->programCounter, 5, ' ', STR_PAD_LEFT) . ': ';
				
				switch($opcode) {
					case OPCODE_EXIT:
						$this->executionLog .= "EXIT\n";
						break 2;
					
					case OPCODE_CHICKEN:
						array_push($this->stack, 'chicken');
						$this->executionLog .= 'PUSH "chicken"';
						break;
					
					case OPCODE_ADD:
						$a = array_pop($this->stack);
						$b = array_pop($this->stack);
						$result = self::addJS($b, $a);
						array_push($this->stack, $result);
						$this->executionLog .= 'ADD ' . self::toStringJS($b) . ' + ' . self::toStringJS($a) . ' = ' .
								self::toStringJS($result);
						break;
					
					case OPCODE_SUBTRACT:
						$a = array_pop($this->stack);
						$b = array_pop($this->stack);
						$result = self::subtractJS($b, $a);
						array_push($this->stack, $result);
						$this->executionLog .= 'SUBTRACT ' .
								self::toStringJS($b) . ' - ' . self::toStringJS($a) . ' = ' .
								self::toStringJS($result);
						break;
					
					case OPCODE_MULTIPLY:
						$a = array_pop($this->stack);
						$b = array_pop($this->stack);
						array_push($this->stack, $a * $b);
						$this->executionLog .= 'MULTIPLY ' . $a . ' * ' . $b;
						break;
					
					case OPCODE_COMPARE:
						$a = array_pop($this->stack);
						$b = array_pop($this->stack);
						array_push($this->stack, self::isEqualJS($a, $b));
						$this->executionLog .= 'COMPARE ' . self::toStringJS($a) . ' ?= ' . self::toStringJS($b);
						break;
					
					case OPCODE_LOAD:
						$address = array_pop($this->stack);
						$source = $this->stack[++$this->programCounter];
						if(!isset($this->stack[$source][$address]))
							$result = null;
						else
							$result = $this->stack[$source][$address];
						array_push($this->stack, $result);
						$this->executionLog .= "LOAD {$source}/{$address} = " . self::toStringJS($result);
						break;
					
					case OPCODE_STORE:
						$address = array_pop($this->stack);
						$value = array_pop($this->stack);
						$this->stack[$address] = $value;
						$this->executionLog .= 'STORE ' . self::toStringJS($value) . ' AT ' . $address;
						break;
					
					case OPCODE_JUMP:
						$offset = array_pop($this->stack);
						$condition = array_pop($this->stack);
						if(self::toBooleanJS($condition))
							$this->programCounter += $offset;
						$this->executionLog .= 'JUMP IF ' . self::toStringJS($condition) . ' TO ' . $offset;
						break;
					
					case OPCODE_CHAR:
						$value = array_pop($this->stack);
						array_push($this->stack, '&#' . $value . ';');
						$this->executionLog .= 'CHAR ' . $value;
						break;
					
					default: // push n
						$value = $opcode - 10;
						array_push($this->stack, $value);
						$this->executionLog .= 'PUSH ' . $value;
						break;
				}
				
				$this->executionLog .= "\n";
				
				++$this->programCounter;
			}
			
			$output = end($this->stack);
			
			$this->executionLog .= "Executed {$this->executedOpcodes} opcodes. Output: {$output}\n";
			
			return $output;
		}
		
		// ECMAScript Language Specification 9.2
		private static function toBooleanJS($value) {
			if(is_null($value)) // or undefined
				return false;
			if(is_bool($value))
				return $value;
			if(self::isNumberJS($value))
				return !is_nan($value) && (float)$value !== (float)0;
			if(is_string($value))
				return !empty($value);
			if(is_object($value))
				return true;
		}
		
		private static function toStringJS($value) {
			switch(gettype($value)) {
				case 'boolean':
					return $value ? 'true' : 'false';
				case 'integer':
				case 'double':
					if(is_nan($value))
						return 'NaN';
					return (string)$value;
				case 'string':
					return $value;
				case 'array':
					// TODO
					throw new VirtualMachineException('toStringJavaScript(array) is not implemented');
				case 'object':
					// TODO
					throw new VirtualMachineException('toStringJavaScript(object) is not implemented');
				case 'resource':
					// TODO
					throw new VirtualMachineException('toStringJavaScript(resource) is not implemented');
				case 'NULL':
				case 'unknown type':
					return 'undefined';
			}
		}
		
		private static function addJS($a, $b) {
			if(self::isNumberJS($a) && self::isNumberJS($b))
				return $a + $b;
			else
				return self::toStringJS($a) . self::toStringJS($b);
		}
		
		private static function subtractJS($a, $b) {
			if(self::isNumberJS($a) && self::isNumberJS($b))
				return $a - $b;
			elseif(is_numeric($a) && is_numeric($b))
				return (float)$a - (float)$b;
			else
				return NAN;
		}
		
		private static function isEqualJS($a, $b) {
			if((is_float($a) && is_nan($a)) || (is_float($b) && is_nan($b)))
				return false;
			if(is_string($a) || is_string($b))
				return self::toStringJS($a) === self::toStringJS($b);
			return $a == $b;
		}
		
		private static function isNumberJS($value) {
			return is_int($value) || is_float($value);
		}
	}