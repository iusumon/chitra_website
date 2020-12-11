<?php

class Model_convert_number extends Model {

    function __construct() {
        parent::Model();
    }
    //============================================================================================
    function int_to_words($x) {
        $nwords = array("Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen", "Twenty", 30 => "Thirty", 40 => "Forty", 50 => "Fifty", 60 => "Sixty", 70 => "Seventy", 80 => "Eighty", 90 => "Ninety");
        if (!is_numeric($x)) {
            $w = '#';
        } else if (fmod($x, 1) != 0) {
            $w = '#';
        } else {
            if ($x < 0) {
                $w = 'minus ';
                $x = -$x;
            } else {
                $w = '';
            }
            if ($x < 21) {
                $w .= $nwords[$x];
            } elseif ($x < 100) {
                $w .= $nwords[10 * floor($x / 10)];
                $r = fmod($x, 10);
                if ($r > 0) {
                    $w .= '-' . $nwords[$r];
                }
            } elseif ($x < 1000) {
                $w .= $nwords[floor($x / 100)] . ' Hundred';
                $r = fmod($x, 100);
                if ($r > 0) {
                    $w .= ' and ' . $this->int_to_words($r);
                }
            } elseif ($x < 100000) {
                $w .= $this->int_to_words(floor($x / 1000)) . ' Thousand';
                $r = fmod($x, 1000);
                if ($r > 0) {
                    $w .= ' ';
                    if ($r < 100) {
                        $w .= 'and ';
                    }
                    $w .= $this->int_to_words($r);
                }
            } elseif ($x < 100000) {
                $w .= $this->int_to_words(floor($x / 1000)) . ' Thousand';
                $r = fmod($x, 1000);
                if ($r > 0) {
                    $w .= ' ';
                    if ($r < 100) {
                        $w .= 'and ';
                    }
                    $w .= $this->int_to_words($r);
                }
            } elseif ($x < 1000000) {
                $w .= $this->int_to_words(floor($x / 100000)) . ' Lakh';
                $r = fmod($x, 100000);
                if ($r > 0) {
                    $w .= ' ';
                    if ($r < 100) {
                        $word .= 'and ';
                    }
                    $w .= $this->int_to_words($r);
                }
            } else {
                $w .= $this->int_to_words(floor($x / 10000000)) . ' Crore';
                $r = fmod($x, 10000000);
                if ($r > 0) {
                    $w .= ' ';
                    if ($r < 100) {
                        $word .= 'and ';
                    }
                    $w .= $this->int_to_words($r);
                }
            }
        }
        return $w;
    }
    //============================================================================================
    function number_to_words($number) {
        if ($number > 999999999) {
            throw new Exception("Number is out of range");
        }

//        $Gn = floor($number / 100000000);  /* Crores */
//        $number -= $Gn * 100000000;
        $Gn = floor($number / 100000);  /* Lakhs (giga) */
        $number -= $Gn * 100000;
        $kn = floor($number / 1000);     /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);      /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);       /* Tens (deca) */
        $n = $number % 10;               /* Ones */
        $cn = round(($number - floor($number)) * 100); /* Cents */
        $result = "";

//        if ($Gn) {
//            $result .= $this->number_to_words($Gn) . " Crore ";
//        }
        
        if ($Gn) {
            $result .= $this->number_to_words($Gn) . " Lakh";
        }

        if ($kn) {
            $result .= ( empty($result) ? "" : " ") . $this->number_to_words($kn) . " Thousand";
        }

        if ($Hn) {
            $result .= ( empty($result) ? "" : " ") . $this->number_to_words($Hn) . " Hundred";
        }

        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
            "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
            "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
            "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
            "Seventy", "Eighty", "Ninety");

        if ($Dn || $n) {
            if (!empty($result)) {
                $result .= " ";
            }

            if ($Dn < 2) {
                $result .= $ones[$Dn * 10 + $n];
            } else {
                $result .= $tens[$Dn];
                if ($n) {
                    $result .= "-" . $ones[$n];
                }
            }
        }

        if ($cn) {
            if (!empty($result)) {
                $result .= ' and ';
            }
            $title = $cn == 1 ? 'Paisa ' : 'Paisa';
            $result .= strtolower($this->number_to_words($cn)) . ' ' . $title;
        }

        if (empty($result)) {
            $result = "zero";
        }

        return $result;
    }
    //============================================================================================
}

?>
