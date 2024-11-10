<?php

/**
* 
* Submit HTML form with (by, from) PHP.
* Depends on:
*  1) cURL (https://secure.php.net/manual/en/book.curl.php)
*  2) Simple HTML DOM (http://simplehtmldom.sourceforge.net/)
*  3) phpuri (https://github.com/monkeysuffrage/phpuri)
* @version 1.8
* @license GNU Lesser General Public License v3.0 or later
* @author Ivan Mudrik
* @github https://github.com/randomsymbols/fsubmit
*
*/

require_once 'simple_html_dom.php';
require_once 'phpuri.php';

class Fsubmit {
	
	// A URL to download HTML page from.
	// In case 'action' is a relative link, the URL will be used as a base to transform the relative URL to an absolute one.
	// For example: $url = http://ya.ru/search.htm, <form action="gofigure.html" => $action = http://ya.ru/gofigure.html.
	public $url = NULL;
	
	// An HTML page to be used instead of the one located at $url.
	public $html = NULL;
	
	// An ID to get the form by (index/id/name).
	// Use one (and one only) of these variables. 
	// If none set, the first form on the page will be used (index=0).
	public $index = NULL;
	public $id = NULL;
	public $name = NULL;
	
	// Form data to submit (name=>value).
	// If a parameter set here did not exist in the form, it will be added to it.
	// If custom submit name and value - provide them here.
	public $params = array();
	
	// cURL options (http://php.net/manual/en/function.curl-setopt.php).
	// Do not provide form data here, it will be deprecated - provide form data in $params.
	public $curl_opts = array();

	public $enctype	  = null;
	
	// Default cURL options.
	protected $default_curl_opts = array(
		CURLOPT_RETURNTRANSFER=> 1, // Return the transfer as a string instead of printing it to output.
		CURLOPT_SSL_VERIFYPEER=> 0, // Do not verify the peer's SSL certificate.
		CURLOPT_FOLLOWLOCATION=> 1, // Follow HTTP 3xx redirects.
		CURLOPT_COOKIEFILE   => 'cookies.txt', // File name to read cookies from.
		CURLOPT_COOKIEJAR    => 'cookies.txt', // File name to store cookies to.
		CURLOPT_USERAGENT    => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.85 Safari/537.36',
        CURLOPT_HTTPHEADER   =>  array('Accept: application/json','Content-Type: application/json')
	);
	
	public function get_page ( $url = NULL ) {
		/**
		*
		* Gets HTML page from $url or, if it is not set, from $this->url.
		* Returns array:
		* 	Array ( 
		* 		'content' - HTML page returned by server.
		* 		'header' - HTTP header.
		* 	)
		*
		*/
		// Set page URL {
			if ( isset ( $url ) ) {
				$this->url = $url;
			}
			elseif ( isset ( $this->url ) ) {
				$url = $this->url;
			}
			else {
				throw new Exception ( "fsubmit: get_page(): URL is not set, cannot download page from nowhere ಥ﹏ಥ" );
			}
		// }
		// Merge cURL options {
        //echo $this->curl_opts;
			$this->curl_opts = $this->curl_opts + $this->default_curl_opts;
		// }
		// Get page {
			$ch = curl_init( $url );
			curl_setopt_array ( $ch, $this->curl_opts );
			$content = curl_exec( $ch );
			$errno = curl_errno( $ch );
			$errmsg = curl_error( $ch );
			$header = curl_getinfo( $ch );
			curl_close( $ch );
			if ( $errno ) {
				throw new Exception ( "fsubmit: cURL error: $errmsg" );
			}


		// }
		// Return result {
			$result = array(
				'content' => $content,
				'header' => $header
			);
			return $result;
		// }
	}
	
	public function submit () {
		/**
		*
		* Gets HTML form from $this->html, or, if it is not set, from $this->url and submits it.
		* Depends on get_page().
		* Returns array:
		* 	Array ( 
		* 		'content' - HTML page returned by server in response of form submit.
		* 		'header' - HTTP header.
		* 	)
		*
		*/
		// Check input {
			// At least one of form sources (URL/HTML) provided {
				if ( !( $this->url or $this->html ) ) {
					throw new Exception ( "fsubmit: Neither URL nor HTML page are provided to get form from ಥ﹏ಥ" );
				}
			// }
			// At least one and one only form ID (index/id/name) provided {
				$index_set = ( $this->index ) ? true : false;
				$id_set = ( $this->id ) ? true : false;
				$name_set = ( $this->name ) ? true : false;
				$form_id_count = $index_set + $id_set + $name_set;
				// If no form ID provided, use the first form on the page.
				if ( $form_id_count == 0 ) {
					$this->index = 0;
				}
				// If more than 1 form ID is provided, throw an error.
				elseif ( $form_id_count != 1 ) {
					throw new Exception ( "fsubmit: More than one of HTML form identifiers (index, name, ID) is provided, there must be one and one only ಥ﹏ಥ" );
				}
			// }
		// }
		// Get HTML {
			// From URL {
				if ( !$this->html ) {
					$response = $this->get_page ( $this->url ); 
					$html = $response['content'];
					// In case of a redirect.
					$form_url = $response['header']['url'];


				}
			// }
			// From string {
				else {
					$html = $this->html;
					// Form URL from user input.
					if ( $this->url ) {
						$form_url = $this->url;

					}
					// Form URL not provided.
					else {
						$form_url = NULL;
					}
				}
			// }
		// }
		// Parse HTML to DOM {
			$dom = str_get_html ( $html );
		// }
		// }
		// Get form {
			// By index
			if ( isset ( $this->index ) ) {
				$form = $dom->find ( 'form', $this->index );
			}
			// By id
			elseif ( isset ( $this->id ) ) {
				$form = $dom->find ( 'form[id='.$this->id.']', 0 );
			}
			// By name
			elseif ( isset ( $this->name ) ) {
				$form = $dom->find ( 'form[name='.$this->name.']', 0 );
			}
			// Make shure there is a form
			if ( !isset ( $form ) ) {
				throw new Exception ( "fsubmit: No form found in the provided HTML: $html" );
			}
		// }
		// Parse form {
			// Get method {
				$method = strtoupper( $form->method );
				if ( !isset ( $method ) or empty ( $method ) ) {
					$method = 'GET';
				}
			// }
			// Get enctype {
				$this->enctype = strtolower( $form->enctype );
				if ( !isset ( $this->enctype ) or empty ( $this->enctype ) or $method == 'GET' ) {
                    $this->enctype = 'application/x-www-form-urlencoded';
				}
			// }
			// Get action {
				$action = $form->action;
				// Action is empty {
					if ( !isset ( $action ) or empty ( $action ) ) {
						if ( $form_url ) {
							$action = $form_url;
						}
						else {
							throw new Exception ( "fsubmit: Form's 'action' is empty AND no URL is provided in '->url' variable, don't know where to submit the form to ಥ﹏ಥ" );
						}
					}
				// }
				// Action is a relative link {
					elseif ( !filter_var ( $action, FILTER_VALIDATE_URL ) ) {
						if ( $form_url ) {
							$action = phpUri::parse( $form_url )->join( $action );
						}
						else {
							throw new Exception ( "fsubmit: Form's 'action' is a relative link AND no URL is provided in '->url' variable, don't know where to submit the form to ಥ﹏ಥ" );
						}
					}
				// }
			// }
			// Get form data {
				$parsed_params = array();
				if ( $form->id ) {
					$form_elements = $dom->find ( "button,button[form=$form->id],input,input[form=$form->id],select,select[form=$form->id],textarea,textarea[form=$form->id]" );
				}
				else {
					$form_elements = $dom->find ( 'button,input,select,textarea' );
				}
				foreach ( $form_elements as $element ) {
					$tag = strtolower ( $element->tag );
					$type = strtolower ( $element->type );
					// Ignore disabled elements {
						if ( $element->disabled ) { 
							continue;
						}
					// }
					// Ignore elements without a name {
						if ( !isset ( $element->name ) or empty ( $element->name ) ) { 
							continue;
						}
					// }
					// input {
						if ( $tag == 'input' ) {
							if ( $type == 'checkbox' or $type == 'radio' ) {
								if ( $element->checked ) {
									if ( $element->value ) {
										$parsed_params[$element->name] = $element->value;
									}
									else {
										$parsed_params[$element->name] = 'on';
									}
								}
							}
							elseif ( $type != 'button' and $type != 'image' and $type != 'reset' and $type != 'submit' ) {
								if ( $element->value ) {
									$parsed_params[$element->name] = $element->value;
								}
								else {
									$parsed_params[$element->name] = '';
								}
							}
						}
					// }
					// textarea {
						if ( $tag == 'textarea' ) {
							$parsed_params[$element->name] = $element->innertext;
						}
					// }
					// select {
						if ( $tag == 'select' ) {
							// Selected option
							$selected = $element->find ( 'option[selected]', 0 );
							if ( $selected ) {
								if ( !($selected->disabled or $selected->parent()->disabled) ) {
									$option = $selected;
								}
							}
							// First option in the list
							else {
								foreach ( $element->find ( 'option' ) as $first ) {
									if ( $first->disabled or $first->parent()->disabled ) {
										continue;
									}
									else {
										$option = $first;
										break;
									}
								}
							}
							if ( isset ( $option ) ) {
								if ( $option->value ) {
									$parsed_params[$element->name] = $option->value;
								}
								else {
									$parsed_params[$element->name] = $option->innertext;
								}
							}
						}
					// }
				}
			// }
		// Prepare form for submitting {
			// Merge form data {

		//Verificar si los parametros es un JSON
		@json_decode($this->params,TRUE);

        //$params = $this->params;
        if (json_last_error() === JSON_ERROR_NONE) {
            $params = /*json_decode(*/$this->params/*,TRUE)*/;
            //print_r($params);
        }else{
            $params = $this->params + $parsed_params;
           // print_r($params);
		}

        //

			// }

			// Encode form data {
			if ( $this->enctype == 'application/x-www-form-urlencoded' ) {
                $params  = http_build_query( $params );
			}

			// }
			// Merge cURL options {
				$this->curl_opts = $this->curl_opts + $this->default_curl_opts;
			// }
			// Adjust cURL options according to method {
				switch ( $method ) {
					case 'GET':
						$action = $action . '?' . $params;
						break;
					case 'POST':
						//echo $params;
						$this->curl_opts[CURLOPT_POST] = false;
						$this->curl_opts[CURLOPT_POSTFIELDS] = $params;
						break;
				}
			// }
		// }
		// Submit {
        	$ch = curl_init($action);
        	curl_setopt_array ( $ch, $this->curl_opts );

			$content= curl_exec( $ch );
			$errno 	= curl_errno( $ch );
			$errmsg = curl_error( $ch );
			$header = curl_getinfo( $ch );


			curl_close( $ch );
			if ( $errno ) {
				throw new Exception ( "fsubmit: cURL error: $errmsg" );
			}
		// }
		// Return result {
			$result = array(
				'content' => $content,
				'header'  => $header
			);
			return $result;
		// }
	}
}
?>
