<?php
/**
 * DokuWiki Plugin dlcounter (Action Component)
 *
 * records and displays download counts for files with specified extensions in the media library
 *
 * @author Phil Ide <phil@pbih.eu>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}

class action_plugin_dlcounter extends DokuWiki_Action_Plugin
{

    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     *
     * @return void
     */
    public function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('MEDIA_SENDFILE', 'BEFORE', $this, 'handle_media_sendfile');
   
    }

    /**
     * [Custom event handler which performs action]
     *
     * Called for event:
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     *
     * @return void
     */
    public function handle_media_sendfile(Doku_Event $event, $param)
    {
        $data = $event->data;
        $extension = str_replace(' ', '', strtolower($this->getConf('extensions')) );
        $extension = explode( ",", $extension );
        $ok = true;

        if( in_array( strtolower($data['ext']), $extension ) ){
            $path = DOKU_INC."/data/counts";
            if( !file_exists($path) ){
                $ok = mkdir($path,0755);
            }
            if( $ok ){
                $fname = $path.'/download_counts.json';
                $json = array();
                if( file_exists( $fname ) ){
                    $json = json_decode( file_get_contents($fname), TRUE );
                }

                $count = 0;
                if( array_key_exists($data['media'], $json) ){
                    $count = $json[$data['media']];
                }
                $count++;
                $json[$data['media']] = $count;

                file_put_contents( $fname, json_encode($json) );
            }
        }
    }

}

