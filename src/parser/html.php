<?php

function dp($txt, $var){

  $trans = get_html_translation_table(HTML_ENTITIES);
  $encoded = strtr($var, $trans);

  printf("<br>%s is now: %s", $txt, $encoded);

}
 
function parse($html) {

  $html2 = $html;
  $tmp = $html2;
    
  $c=0;
    
  while (($tmp[$c] || $c < strlen($tmp))) {

      if ($tmp[$c] == '<' || $istag) {

        $istag = 0;
              
          if ($tmp[$c++] == '!') {
            
            if ($tmp[$c++] == '-' || $tmp[$c+1] == '-') {
                  
              $c+=2;
              
              while ($tmp[$c] == ' ') $c++;

              $c--;
                    
              startCommentCallback();
                    
              $t = $c;

              while (($tmp[$t] || $t < strlen($tmp)) && !($tmp[$t] == '-' && $tmp[$t++] == '-' && $tmp[$t+2] == '>')) $t++;

                if (($tmp[$t] || $t < strlen($tmp))) {

                  while ($tmp[$t] == ' ') $t--;

                  $comment = substr($tmp, $c, $t-$c);

                  while ($tmp[$t] == ' ') $t++;

                  $t+=2;
                  $c = $t;

                }
                    
                commentCallback($comment);
                endCommentCallback();
                  
                $c++;

            } else {
              
              $c--;
              
            }

        } elseif ($tmp[$c] == '/' || $tmp[$c-1] == '/') {

          if ($tmp[$c] == '/') $c++;

          $t=$c;

          while (($tmp[$t] || $t < strlen($tmp)) && $tmp[$t] != '>') $t++;
            
          $tag = substr($tmp,$c,$t-$c);
          endCallback($tag);
          $t++;
          $c = $t;

          continue;

        } else {
          
          if ($tmp[$c-1] != '<') $c--;
                
          if ($tmp[$c] == '!' && $tmp[$c++] == '-') comment();
            
          $t = $c;
          $q = $c;
          $tagstart = $c;
                
          $tag = substr($tmp,$c);

          while ($tmp[$t] != '>' && $tmp[$t] != ' ') $t++;

          if ($tmp[$t] == '>') {

              $tag = substr($tmp, $c, $t-$c);

              if ($tag[0] == '!') {

                  $tag = substr($tmp,$c+1, $t-($c+1));
                  $tagstart = $c+1;
                  declCallback($tag, "", 0);

              } else startCallback($tag, "", 0);
                
              $c = $t+1;
                
              continue;

          } elseif ($tmp[$c] == ' ') {

            while ($tmp[$c] == ' ') $c++;

          } else {

            if ($tmp[$q] == '!') {

              $q++;
              $tag = substr($tmp, $q, $q-$c);
              declCallback ($tag, "", 0);

            } else {

              declCallback ($tag, "", 0);

            }

            break;

          }
                
          unset($args);
          $numargs = 0;
                
          while (($tmp[$c] || $c < strlen($tmp))) {

          $istrue = 0;
          $tagended = 0;

          while ($tmp[$c] == ' ') $c++;

          if (!$tmp[$c-1] == ' ') $c--;
                    
          $arg = $c;
          
          if ($tmp[$arg] == '"' || $tmp[$arg] == "'"){

            $c++;
            $arg = $c;

            while (($tmp[$c] || $c < strlen($tmp)) && !($tmp[$c] == '"' && $tmp[$c-1] != '' && $tmp[$c] != "'")) $c++;
              
            if ($tmp[$c] != '>') continue;

            if ($tmp[$c+1] == '>') {

              $c++;

            }
            
            break;

          }
                    
          $val = "";

          while ($tmp[$c] != '=' && $tmp[$c] != ' ' && $tmp[$c] != '>') $c++;

          if ($tmp[$c] != ' ' && $tmp[$c] != '>') $istrue = 1;

          if ($tmp[$c] == '>') $tagended = 1;
                    
          $q = $c;
          $c++;
                    
          if ($istrue) {

            if ($tmp[$c] != "'" && $tmp[$c] != '"') {

              while ($tmp[$c] != ' ' && $tmp[$c] != '>') $c++;

              if ($tmp[$c] == '>') {

                $val = substr($tmp,$q, $c-$q);

              } else {

                $c++;
                $val = substr($tmp, $c, $c-$q);
                
                continue;

              }

            } else {

              $c++;

              while ($tmp[$c] && ($tmp[$c] != "'" || ($tmp[$c] == "'" && $tmp[$c-1] == '')) && ($tmp[$c] != '"' || ($tmp[$c] == '"' && $tmp[$c-1] == ''))) $c++;
                  
              if ($tmp[$c] == '>') {

                $val = substr($tmp,$q, $c-$q);
                $c++;
                
                break;
                    
              } elseif ($tmp[$c+1] == '>') {

                $val = substr($tmp, $q, $c-$q);
                $c++;
                
                break;

              } else {

                $val = substr($tmp, $q, $c-$q);
                $c+=2;

              }

            }

          } else {
            
            if (!$tagended) continue;

            $tagended = 0;
            $c--;

            break;

          }
      }
                
      $q=0;

      if ($tag[$q] == '!') {

        $q++;
        
        $tag = substr($tag, $q);
        
        declCallback($tag, "", 0);

      } else {
        
        startCallback($tag, "", 0);

      }
      
      $c++;

      continue;

      }

    } else {
                            
      if ($tmp[$c] == 'n') {

        $c++;

        continue;

      }
        
      $text = $tmp;
      $q = $c;
        
      if ($text[$q] == '!') {

        $q--;

        if ($text[$q-1] == '<') {

          $q--;

          continue;

        }

      }                
        
      while ($tmp[$c] == ' ' && $tmp[$c] != '<' && ($tmp[$c] || $c < strlen($tmp))) $c++;

      if ($tmp[$c] == '<' && $tmp[$c+1]) {

        continue;

      } else if (!($tmp[$c] || $c < strlen($tmp))) break;

      textStartCallback();
        
      for (;;) {

        while (($tmp[$c] || $c < strlen($tmp)) && $tmp[$c] != '<') $c++;

        if ($tmp[$c] == '<') {

          if ($tmp[$c+1] == ' ') {
            
            $c++;

            continue;

          } else $istag = 1;

        }
        
        break;

      }
            
      $text = substr($tmp, $q, $c-$q);
        
      textCallback($text);
        
      textEndCallback();

      $c++;

      continue;
        
    }
    
  }
            
  return;

}
     
?> 