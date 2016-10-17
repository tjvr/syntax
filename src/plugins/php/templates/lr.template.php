<?php

/**
 * LR parser generated by the Syntax tool.
 *
 * https://www.npmjs.com/package/syntax-cli
 *
 *   npm install -g syntax-cli
 *
 *   syntax-cli --help
 *
 * To regenerate run:
 *
 *   syntax-cli \
 *     --grammar ~/path-to-grammar-file \
 *     --mode <parsing-mode> \
 *     --output ~/path-to-output-parser-file.js
 */

<<MODULE_INCLUDE>>

class yyparse {
  private static $ps = <<PRODUCTIONS>>;
  private static $tks = <<TOKENS>>;
  private static $tbl = <<TABLE>>;

  private static $s = [];
  private static $__ = null;

  private static $on_parse_begin = null;
  private static $on_parse_end = null;

  public static $yytext = '';
  public static $yyleng = 0;

  const EOF = '$';

  private static $tokenizer = null;

  <<PRODUCTION_HANDLERS>>

  public static function setTokenizer($tokenizer) {
    self::$tokenizer = $tokenizer;
  }

  public static function getTokenizer() {
    return self::$tokenizer;
  }

  public static function setOnParseBegin($on_parse_begin) {
    self::$on_parse_begin = $on_parse_begin;
  }

  public static function setOnParseEnd($on_parse_end) {
    self::$on_parse_end = $on_parse_end;
  }

  public static function parse($string) {
    if (self::$on_parse_begin) {
      $on_parse_begin = self::$on_parse_begin;
      $on_parse_begin($string);
    }

    $tokenizer = self::getTokenizer();

    if (!$tokenizer) {
      throw new Exception(`Tokenizer instance wasn't specified.`);
    }

    $tokenizer->initString($string);

    $s = &self::$s;
    $s = ['0'];

    $tks = &self::$tks;
    $tbl = &self::$tbl;
    $ps = &self::$ps;

    $t = $tokenizer->getNextToken();
    $st = null;

    do {
      if (!$t) {
        self::unexpectedEndOfInput();
      }

      $sta = end($s);
      $clm = $tks[$t['type']];
      $e = $tbl[$sta][$clm];

      if (!$e) {
        self::unexpectedToken(t);
      }

      if ($e[0] === 's') {
        array_push(
          $s,
          array('symbol' => $tks[$t['type']], 'semanticValue' => $t['value']),
          intval(substr($e, 1))
        );
        $st = $t;
        $t = $tokenizer->getNextToken();
      } else if ($e[0] === 'r') {
        $pn = intval(substr($e, 1));
        $p = $ps[$pn];
        $hsa = count($p) > 2;
        $saa = hsa ? [] : null;

        if ($p[1] !== 0) {
          $rhsl = $p[1];
          while ($rhsl-- > 0) {
            array_pop($s);
            $se = array_pop($s);

            if ($hsa) {
              array_unshift($saa, $se['semanticValue']);
            }
          }
        }

        $rse = array('symbol' => $p[0]);

        if ($hsa) {
          self::$yytext = $st ? $st['value'] : null;
          self::$yyleng = $st ? strlen($st['value']) : null;

          forward_static_call_array(array('self', $p[2]), $saa);
          $rse['semanticValue'] = self::$__;
        }

        array_push(
          $s,
          $rse,
          $tbl[end($s)][$p[0]]
        );
      } else if ($e === 'acc') {
        array_pop($s);
        $parsed = array_pop($s);

        if (count($s) !== 1 ||
            $s[0] !== '0' ||
            $tokenizer->hasMoreTokens()) {
          self::unexpectedToken($t);
        }

        $parsed_value = array_key_exists('semanticValue', $parsed)
          ? $parsed['semanticValue']
          : true;

        if (self::$on_parse_end) {
          $on_parse_end = self::$on_parse_end;
          $on_parse_end($parsed_value);
        }

        return $parsed_value;
      }

    } while ($tokenizer->hasMoreTokens() || count($s) > 1);
  }

  public static function unexpectedToken($token) {
    if ($token['value'] === self::EOF) {
      unexpectedEndOfInput();
    }
    self::parseError('Unexpected token: '.$token['value']);
  }

  public static function unexpectedEndOfInput() {
    self::parseError('Unexpected end of input.');
  }

  public static function parseError($message) {
    throw new Exception('Parse error: '.$message);
  }
}

<<TOKENIZER>>

class <<PARSER_CLASS_NAME>> extends yyparse {}

