<?php

class Locator
{
    public static function toXpath($expression)
    {
        if (preg_match('~^(tx:|xp:|//|\./).*~', $expression, $matches)) {
            if ($matches[1] == 'xp:') {
                return substr($expression, 3);
            } else if ($matches[1] == 'tx:') {
                return '//*[text()="' . substr($expression, 3) . '"]';
            }
            return $expression;
        } else {
            if (strpos('#', $expression) == 0) {
                $expression = '*' . $expression;
            }
            return self::cssToXpath($expression);
        }
    }

    /**
     * @source http://www.webdesignerforum.co.uk/topic/2325-css-to-xpath-in-php/
     */
    public static function cssToXpath($rule)
    {
        $reg['element']    = "/^([#.]?)([a-z0-9\\*_-]*)((\|)([a-z0-9\\*_-]*))?/i";
        $reg['attr1']      = "/^\[([^\]]*)\]/i";
        $reg['attr2']      = '/^\[\s*([^~=\s]+)\s*(~?=)\s*"([^"]+)"\s*\]/i';
        $reg['attrN']      = "/^:not\((.*?)\)/i";
        $reg['pseudo']     = "/^:([a-z_-])+/i";
        $reg['gtlt']       = "/^:([g|l])t\(([0-9])\)/i";
        $reg['last']       = "/^:(last |last\([-]([0-9]+)\))/i";
        $reg['first']      = "/^:(first\([+]([0-9]+)\)|first)/i";
        //$reg['first']    = "/^:(first |first\([+]([0-9]+)\))/i";
        $reg['pseudoN']    = "/^:nth-child\(([0-9])\)/i";
        $reg['combinator'] = "/^(\s*[>+\s])?/i";
        $reg['comma']      = "/^\s*,/i";

        $index = 1;
        $parts = array('//');
        $lastRule = NULL;

        while( strlen($rule) > 0 && $rule != $lastRule ) {
            $lastRule = $rule;
            $rule = trim($rule);
            if( strlen($rule) > 0) {
                // Match the Element identifier
                $a = preg_match( $reg['element'], $rule, $m );
                if( $a ) {

                    if( !isset($m[1]) )
                    {

                        if( isset( $m[5] ) ) {
                            $parts[$index] = $m[5];
                        } else {
                            $parts[$index] = $m[2];
                        }  }
                        else if( $m[1] == '#') {
                            array_push($parts, "[@id='".$m[2]."']");
                        } else if ( $m[1] == '.' ) {
                            array_push($parts, "[contains(@class, '".$m[2]."')]");
                        }else {
                            array_push( $parts, $m[0] );
                        }
                    $rule = substr($rule, strlen($m[0]) );
                }

                // Match attribute selectors.
                $a = preg_match( $reg['attr2'], $rule, $m );
                if( $a ) {
                    if( $m[2] == "~=" ) {
                        array_push($parts, "[contains(@".$m[1].", '".$m[3]."')]");
                    } else {
                        array_push($parts, "[@".$m[1]."='".$m[3]."']");
                    }
                    $rule = substr($rule, strlen($m[0]) );
                } else {
                    $a = preg_match( $reg['attr1'], $rule, $m );
                    if( $a ) {
                        array_push( $parts, "[@".$m[1]."]");
                        $rule = substr($rule, strlen($m[0]) );
                    }
                }

                // register nth-child
                $a = preg_match( $reg['pseudoN'], $rule, $m );
                if( $a ) {
                    array_push( $parts, "[".$m[1]."]" );
                    $rule = substr($rule, strlen($m[0]));
                }

                // gt and lt commands
                $a = preg_match( $reg['gtlt'], $rule, $m );
                if( $a ) {
                    if( $m[1] == "g" ) {
                        $c = ">";
                    } else {
                        $c = "<";
                    }

                    array_push( $parts, "[position()".$c.$m[2]."]" );
                    $rule = substr($rule, strlen($m[0]));
                }

                // last and last(-n) command
                $a = preg_match( $reg['last'], $rule, $m );
                if( $a ) {
                    if( isset( $m[2] ) ) {
                        $m[2] = "-".$m[2];
                    }
                    array_push( $parts, "[last()".$m[2]."]" );
                    //print_r($m);
                    $rule = substr($rule, strlen($m[0]));
                }

                // first and first(+n) command
                $a = preg_match( $reg['first'], $rule, $m );
                if( $a ) {
                    $n = 0;
                    if( isset( $m[2] ) ) {
                        $n = $m[2];
                    }
                    array_push( $parts, "[$n]" );

                    $rule = substr($rule, strlen($m[0]));

                }


                // skip over pseudo classes and pseudo elements
                $a = preg_match( $reg['pseudo'], $rule, $m );
                while( $m ) {
                    // loop???
                    $rule = substr( $rule, strlen( $m[0]) );
                    $a = preg_match( $reg['pseudo'], $rule, $m );
                }

                // Match combinators
                $a = preg_match( $reg['combinator'], $rule, $m );
                if( $a && strlen($m[0]) > 0 ) {
                    if( strpos($m[0], ">") ) {
                        array_push( $parts, "/");
                    } else if( strpos( $m[0], "+") ) {
                        array_push( $parts, "/following-sibling::");
                    } else {
                        array_push( $parts, "//" );
                    }

                    $index = count($parts);
                    //array_push( $parts, "*" );
                    $rule = substr( $rule, strlen( $m[0] ) );
                }

                $a = preg_match( $reg['comma'], $rule, $m );
                if( $a ) {
                    array_push( $parts, " | ", "//" );
                    $index = count($parts) -1;
                    $rule = substr( $rule, strlen($m[0]) );
                }
            }
        }
        $xpath = implode("",$parts);
        return $xpath;
    }
}
