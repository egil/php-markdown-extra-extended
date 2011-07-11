<?php
define( 'MARKDOWNEXTRAEXTENDED_VERSION',  "0.2" ); # 7/11/2011
define( 'MARKDOWN_PARSER_CLASS',  'MarkdownExtraExtended_Parser' );

require_once('markdown.php');

class MarkdownExtraExtended_Parser extends MarkdownExtra_Parser {
	
	public static $default_classes = array();
	
	function MarkdownExtraExtended_Parser() {
		$this->document_gamut += array(
			"doAddDefaultClasses" => 1000
		);		
		parent::MarkdownExtra_Parser();
	}
	
	function doAddDefaultClasses($text) {	
		// Dont wast time if there is no default classes defined
		if(!empty(self::$default_classes)){
			$doc = new DOMDocument();
			$doc->loadHTML($text);
			$xpath = new DOMXpath($doc);			

			// Iterate over all default classes tag-class sets
			// and update each tag with the classes.
			foreach (self::$default_classes as $tag => $classes){
				$query = '//' . $tag . '[not(ancestor::code)]';
				foreach($xpath->query($query) as $element){
					$classAttr = trim($element->getAttribute('class'));
					$classAttr .= empty($classAttr) ? $classes : " $classes";
					$element->setAttribute("class", $classAttr);
				}
			}
			
			// save the updated html
			$text = $doc->saveHTML();
		}
		return $text;
	}	
	
	function doHardBreaks($text) {
		# Do hard breaks:
		# EXTENDED: changed to allow breaks without two spaces and just one new line
		# original code /* return preg_replace_callback('/ {2,}\n/', */
		return preg_replace_callback('/ *\n/', 
			array(&$this, '_doHardBreaks_callback'), $text);
	}

	function doBlockQuotes($text) {
		$text = preg_replace_callback('/
			(?>[ ]*>[ ]?
				(?:\((.+?)\))?
				[ ]*(.+\n(?:.+\n)*)
			)+	
			/xm',
			array(&$this, '_doBlockQuotes_callback'), $text);

		return $text;
	}
	
	function _doBlockQuotes_callback($matches) {
		$cite = $matches[1];
		$bq = '> ' . $matches[2];
		# trim one level of quoting - trim whitespace-only lines
		$bq = preg_replace('/^[ ]*>[ ]?|^[ ]+$/m', '', $bq);
		$bq = $this->runBlockGamut($bq);		# recurse

		$bq = preg_replace('/^/m', "  ", $bq);
		# These leading spaces cause problem with <pre> content, 
		# so we need to fix that:
		$bq = preg_replace_callback('{(\s*<pre>.+?</pre>)}sx', 
			array(&$this, '_doBlockQuotes_callback2'), $bq);
		
		$res = "<blockquote";
		$res .= empty($cite) ? ">" : " cite=\"$cite\">";
		$res .= "\n$bq\n</blockquote>";
		return "\n". $this->hashBlock($res)."\n\n";
	}

	function doFencedCodeBlocks($text) {
		$less_than_tab = $this->tab_width;
		
		$text = preg_replace_callback('{
				(?:\n|\A)
				# 1: Opening marker
				(
					~{3,}|`{3,} # Marker: three tilde or more.
				)
				
				[ ]?(\w+)?[ ]* \n # Whitespace and newline following marker.
				
				# 3: Content
				(
					(?>
						(?!\1 [ ]* \n)	# Not a closing marker.
						.*\n+
					)+
				)
				
				# Closing marker.
				\1 [ ]* \n
			}xm',
			array(&$this, '_doFencedCodeBlocks_callback'), $text);

		return $text;
	}
	
	function _doFencedCodeBlocks_callback($matches) {
		$codeblock = $matches[3];
		$codeblock = htmlspecialchars($codeblock, ENT_NOQUOTES);
		$codeblock = preg_replace_callback('/^\n+/',
			array(&$this, '_doFencedCodeBlocks_newlines'), $codeblock);
		//$codeblock = "<pre><code>$codeblock</code></pre>";
		$cb = "<pre><code";
		$cb .= empty($matches[2]) ? ">" : " class=\"language-$matches[2]\">";
		$cb .= "$codeblock</code></pre>";
		return "\n\n".$this->hashBlock($cb)."\n\n";
	}

}
?>