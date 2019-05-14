<?php
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

/**
 * DokuWiki Plugin dlcounter (Syntax Component)
 *
 * @author Phil Ide <phil@pbih.eu>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

class syntax_plugin_dlcounter extends DokuWiki_Syntax_Plugin {

    /**
     * What kind of syntax are we?
     */
    function getType() {
        return 'substition';
    }

    /**
     * What about paragraphs?
     */
    function getPType() {
        return 'normal';
    }

    /**
     * Where to sort in?
     */
    function getSort() {
        return 155;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\{\{dlcounter>[^\}]+\}\}', $mode, 'plugin_dlcounter');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler) {
        if (isset($_REQUEST['comment'])) {
            return false;
        }
        $command = trim(substr($match, 12 ,-2));
        $x = explode('?', $command);
        $command = $x[0];
        $params = explode(' ', $x[1]);

        $data = array(
                    'command' => $command,
                    'file'    => '',
                    'sort'    => 'none',
                    'strip'   => false,
                    'align'   => 'right',
                    'minwidth' => 0,
                    'cpad'    => 1,
                    'halign'  => 'center',
                    'bold'    => 'b',
                    'header'  => true,
                    'htext'   => 'Downloads'
                );

        foreach( $params as $item ){
            // switch turns out to be buggy for multiple iterations - grrrrr!
            if( $item == 'sort' )          $data['sort'] = $item;
            else if( $item == 'rsort' )    $data['sort'] = $item;
            else if( $item == 'strip' )    $data['strip'] = true;
            else if( $item == 'left' )     $data['align'] = $item;
            else if( $item == 'center' )   $data['align'] = $item;
            else if( $item == 'right' )    $data['align'] = $item;
            else if( $item == 'hleft' )    $data['halign'] = 'left';
            else if( $item == 'hcenter' )  $data['halign'] = 'center';
            else if( $item == 'hright' )   $data['halign'] = 'right';
            else if( $item == 'nobold' )   $data['bold'] = 'nobold';
            else if( $item == 'noheader' ) $data['header'] = false;
            else if( substr( $item, 0, 6) == 'htext=' ){
                $data['htext'] = explode('"', $x[1])[1];
            }
            else if( substr( $item, 0, 9) == 'minwidth=' ){
                $data['minwidth'] = explode('=', $item)[1];
            }
            else if( substr( $item, 0, 5) == 'cpad=' ){
                $data['cpad'] = explode('=', $item)[1];
            }
            else $data['file'] = $item;
        }
        return $data;
    }


    /**
     * Create output
     */
    function render($format, Doku_Renderer $renderer, $data) {
        $fname = DOKU_INC.'data/counts/download_counts.json';
        $json = json_decode( file_get_contents($fname), TRUE );

        $command = $data['command'];
        $file = $data['file'];

        //$renderer->doc .= "<pre>".print_r($data, true)."</pre>"; // for debugging

        if( $command == 'file' ){
            // just want a counter
            $count = 0;
            if( $file != "" && array_key_exists( $file, $json ) ){
                $count = $json[$file];
                $renderer->doc .= $count;
            }
        }
        else {
            // dump all the data in a table
            $sort = $data['sort'] == '' ? 'sort' : $data['sort'];

            if( $command == 'name' ){
                if( $sort == 'sort' ) ksort($json);
                else if( $sort == 'rsort' ) krsort($json);
            }
            else if( $command == 'count' ){
                if( $sort == 'sort' ) asort( $json );
                else if( $sort == 'rsort' ) arsort( $json );
            }
            $table = "<table>";
            if( $data['header'] ){
                $table .= "<tr><th colspan=2 style='text-align:".$data['halign'].";'>".$data['htext']."</th></tr>";
            }
            foreach( $json as $file => $count ){
                $fdata = explode(':', $file);
                $c = count($fdata);
                $fdata[$c-1] = "<".$data['bold'].">".$fdata[$c-1]."</".$data['bold'].">";
                if( $data['strip'] ) $file = $fdata[$c-1];
                else $file = implode(':', $fdata);
                $table .= "<tr><td style='text-align:".$data['align'].";'>$file</td>".
                            "<td align=right style='text-align: right;min-width: ".$data['minwidth']."em;padding-left: ".$data['cpad']."em;'>$count</td></tr>";
            }
            $table .= "</table>";
            $renderer->doc .= $table;
        }
        return true;
    }
}
