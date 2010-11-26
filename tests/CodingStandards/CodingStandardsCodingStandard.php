<?php

if (class_exists('PHP_CodeSniffer_Standards_CodingStandard', true) === false) {
    throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_CodingStandard not found');
}

class PHP_CodeSniffer_Standards_CodingStandards_CodingStandardsCodingStandard extends PHP_CodeSniffer_Standards_CodingStandard {

    public function getIncludedSniffs()
    {
        return array(
					'Generic/Sniffs/Classes/DuplicateClassNameSniff.php',
					'Generic/Sniffs/CodeAnalysis/ForLoopWithTestFunctionCallSniff.php',
					'Generic/Sniffs/CodeAnalysis/JumbledIncrementerSniff.php',
					'Generic/Sniffs/CodeAnalysis/UnconditionalIfStatementSniff.php',
					'Generic/Sniffs/CodeAnalysis/UnnecessaryFinalModifierSniff.php',
					'Generic/Sniffs/CodeAnalysis/UselessOverridingMethodSniff.php',
					'Generic/Sniffs/ControlStructures/InlineControlStructureSniff.php',
					'Generic/Sniffs/Formatting/DisallowMultipleStatementsSniff.php',
					'Generic/Sniffs/Formatting/MultipleStatementAlignmentSniff.php',
					'Generic/Sniffs/Formatting/NoSpaceAfterCastSniff.php',
					'Generic/Sniffs/Functions/CallTimePassByReferenceSniff.php',
					'Generic/Sniffs/Functions/OpeningFunctionBraceKernighanRitchieSniff.php',
					'Generic/Sniffs/NamingConventions/ConstructorNameSniff.php',
					'Generic/Sniffs/PHP/DisallowShortOpenTagSniff.php',
					'Generic/Sniffs/PHP/ForbiddenFunctionsSniff.php',
					'Generic/Sniffs/PHP/LowerCaseConstantSniff.php',
					'Generic/Sniffs/PHP/NoSilencedErrorsSniff.php',
					'Generic/Sniffs/WhiteSpace/ScopeIndentSniff.php',
					'PEAR/Sniffs/Commenting/FunctionCommentSniff.php',
					'PEAR/Sniffs/Commenting/InlineCommentSniff.php',
					'PEAR/Sniffs/Functions/FunctionCallArgumentSpacingSniff.php',
					'Squiz/Sniffs/Arrays/ArrayBracketSpacingSniff.php',
					'Squiz/Sniffs/Arrays/ArrayDeclarationSniff.php',
					'Squiz/Sniffs/Classes/LowercaseClassKeywordsSniff.php',
					'Squiz/Sniffs/Classes/ValidClassNameSniff.php',
					'Squiz/Sniffs/PHP/GlobalKeywordSniff.php',
					'Squiz/Sniffs/WhiteSpace/SuperfluousWhitespaceSniff.php',
					'Squiz/Sniffs/Operators/IncrementDecrementUsageSniff.php',
		);


    }//end getIncludedSniffs()

}
