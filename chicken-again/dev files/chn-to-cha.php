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

	require_once(__DIR__ . '/ChickenASMLanguage/ChickenASMLanguage.php');
	
	define('PROGRAM_DIR', __DIR__ . '/program');	
	
	echo '<pre>';
	
	$chickenFiles = glob(PROGRAM_DIR . '/*.chn');
	foreach($chickenFiles as $chickenFile) {
		echo $chickenFile . "\n";
		
		try {
			$chickenASMFile = PROGRAM_DIR . '/' . pathinfo($chickenFile, PATHINFO_FILENAME) . '.cha';
			if(is_file($chickenASMFile))
				throw new RuntimeException('Already decompiled');

			$chickenCode = @file_get_contents($chickenFile);
			if($chickenCode === false)
				throw new RuntimeException('Cannot open the file');
			
			$compiler = new ChickenASMLanguage\ChickenCompiler($chickenCode);
			$opcodes = $compiler->compile();
			$decompiler = new ChickenASMLanguage\ChickenASMDecompiler($opcodes);
			$code = $decompiler->decompile();
			file_put_contents($chickenASMFile, $code);
		}
		
		catch(\RuntimeException $e) {
			echo ' * ' . $e->getMessage() . "\n";
		}
	}
	
	echo '</pre>';