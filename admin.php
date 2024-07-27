<?php
/**
 * DokuWiki Plugin dlcounter (Admin Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Phil Ide <phil@pbih.eu>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}

class admin_plugin_dlcounter extends DokuWiki_Admin_Plugin
{

    private $mydata;

    /**
     * @return int sort number in admin menu
     */
    public function getMenuSort()
    {
        return 200;
    }

    /**
     * @return bool true if only access for superuser, false is for superusers and moderators
     */
    public function forAdminOnly()
    {
        return false;
    }

    /**
     * Should carry out any processing required by the plugin.
     */

    public function handle(){
        $data = array(
                    'command' => 'name',
                    'file'    => '',
                    'sort'    => 'sort',
                    'strip'   => true,
                    'align'   => 'right',
                    'minwidth' => 0,
                    'cpad'    => 1,
                    'halign'  => 'center',
                    'bold'    => 'b',
                    'header'  => true,
                    'htext'   => 'Downloads'
                );
        $this->mydata = $data;
    }


    /**
     * Render HTML output, e.g. helpful text and a form
     */

    public function html(){
        $html = "";
        $data = $this->mydata;

        $fname = DOKU_INC.'data/counts/download_counts.json';
        $json = json_decode( file_get_contents($fname), TRUE );

        $command = $data['command'];
        $file = $data['file'];

        if( $command == 'file' ){
            // just want a counter
            $count = 0;
            if( $file != "" && array_key_exists( $file, $json ) ){
                $count = $json[$file];
                $html .= $count;
            }
        }
        else {
            // dump all the data in a table
            $sort = $data['sort'];

            $json = $this->stripKeys( $json );

            if( $command == 'name' ){
                if( $sort == 'sort' ){
                    ksort($json);
                }
                else if( $sort == 'rsort' ){
                    krsort($json);
                }
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
                $table .= "<tr><td style='text-align:".$data['align'].";'>$file</td>".
                            "<td align=right style='text-align: right;min-width: ".$data['minwidth']."em;padding-left: ".$data['cpad']."em;'>$count</td></tr>";
            }
            $table .= "</table>";
            $html .= $table;
        }
        echo $html;
    }

    function stripKeys( $arr ){
        $newArr = array();
        foreach( $arr as $key => $value ){
            $keyX = explode(':',$key);
            $n = count($keyX)-1;
            $newArr[$keyX[$n]] = $value;
        }
        return $newArr;
    }

    function dlcounter_switchKeys( $arr, $back2Front ){
        $keys = array_keys( $arr );
        for( $i = 0; $i < count($keys); $i++ ){
            if( $back2Front ) $keys[$i] = $this->switchKeyHelperA( $keys[$i] );
            else $keys[$i] = $this->switchKeyHelperB( $keys[$i] );
        }
        return array_combine( $keys, $arr );
    }
    
    // move the fileame to the front of the path
    function switchKeyHelperA( $v ){
        $a = explode(':', $v);
        $f = array_pop($a);
        array_unshift( $a, $f );
        return implode(':', $a );
    }
    
    // move the filename from the front of the path to the end
    function switchKeyHelperB( $v ){
        $a = explode(':', $v);
        $f = array_shift($a);
        array_push( $a, $f );
        return implode(':', $a );
    }
}
