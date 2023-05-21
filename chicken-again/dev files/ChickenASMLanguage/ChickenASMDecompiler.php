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
	
	class ChickenASMDecompiler implements Decompiler {
		private $opcodes;
		
		public function __construct($opcodes) {
			$this->opcodes = $opcodes;
		}
		
		public function decompile() {
			$simpleInstructions = array(
				OPCODE_EXIT     => 'exit',
				OPCODE_CHICKEN  => 'chicken',
				OPCODE_ADD      => 'add',
				OPCODE_SUBTRACT => 'subtract',
				OPCODE_MULTIPLY => 'multiply',
				OPCODE_COMPARE  => 'compare',
				OPCODE_STORE    => 'store',
				OPCODE_JUMP     => 'jump',
				OPCODE_CHAR     => 'char'
			);
			$code = '';
			$opcodesCount = count($this->opcodes);
			for($i = 0; $i < $opcodesCount; ++$i) {
				switch($this->opcodes[$i]) {
					case OPCODE_EXIT:
					case OPCODE_CHICKEN:
					case OPCODE_ADD:
					case OPCODE_SUBTRACT:
					case OPCODE_MULTIPLY:
					case OPCODE_COMPARE:
					case OPCODE_STORE:
					case OPCODE_JUMP:
					case OPCODE_CHAR:
						$code .= $simpleInstructions[$this->opcodes[$i]] . "\n";
						break;
					case OPCODE_LOAD:
						++$i;
						if($i < $opcodesCount)
							$source = $this->opcodes[$i];
						else
							$source = 0;
						$code .= 'load ' . $source . "\n";
						break;
					default:
						$code .= 'push ' . ($this->opcodes[$i] - 10) . "\n";
						break;
				}
			}
			return $code;
		}
	}