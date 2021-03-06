<?php

/**
 * BBSmile
 *
 * @license http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_Utility_Modules
 * @subpackage BBSmile
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

class BBSmile_Api_Admin extends Zikula_AbstractApi
{

    /*
    * This function synchronized the smilies stored in the directory with
    * the smilies sored in the modVar ('BBSmile', 'smilie_array).
    * The function checks if there is a smilie in the directory which is
    * not in the modVar. If the smilie is in the modVar, the infos of the
    * modVar are kept. Otherwise the new smilie is addes with it default infos.
    * This function has to be called to include new smilies which are stored in the
    * auto-directory of the module.
    *
    * It can be called out of the admininterface and is called when the module
    * is inited and upgraded
    *
    *@params $args['forcereload'] bool
    */
    public function updatesmilies($args)
    {
        // Get the new array
        $new_smilies = $this->load_smilies();

        // Get the old array
        $old_smilies = $this->getVar('smilie_array');

        //Combine old array and new array
        // First create a new array
        $smilies = array();
        // Then, check if the new smilie is in the old array
        // If it is included then save the old definition
        // Else save the new definition
        foreach($new_smilies as $key => $new_smilie) {
            if($args['forcereload'] == true) {
                $smilies[$key] = $new_smilie;
            } else {
                if (array_key_exists($key, $old_smilies)) {
                    // Store the old one
                    $smilies[$key] = $old_smilies[$key];
                } else {
                    // Store the new one
                    $smilies[$key] = $new_smilie;
                }
            }
        }

        // Save the array
        $this->setVar('smilie_array', $smilies);

        // Return success
        return true;

    }

    /**
    * get all smilies
    */
    public function load_smilies()
    {
        // default smilies
        $imagepath = $this->getVar('smiliepath');
        $icons = array('icon_biggrin.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_biggrin.gif' ,
                'alt'     => 'icon_biggrin',
                'short'   => ':-D',
                'alias'   => '',
                'active'  => '1'),
              'icon_confused.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_confused.gif' ,
                'alt'     => 'icon_confused',
                'short'   => ':-?',
                'alias'   => '',
                'active'  => '1'),
              'icon_cool.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_cool.gif' ,
                'alt'     => 'icon_cool',
                'short'   => '8-)',
                'alias'   => '',
                'active'  => '1'),
              'icon_eek.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_eek.gif' ,
                'alt'     => 'icon_eek',
                'short'   => ':-O',
                'alias'   => '',
                'active'  => '1'),
              'icon_evil.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_evil.gif' ,
                'alt'     => 'icon_evil',
                'short'   => ':evil:',
                'alias'   => ':devil:',
                'active'  => '1'),
              'icon_frown.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_frown.gif' ,
                'alt'     => 'icon_frown',
                'short'   => ':-(',
                'alias'   => '',
                'active'  => '1'),
              'icon_lol.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_lol.gif' ,
                'alt'     => 'icon_lol',
                'short'   => ':lol:',
                'alias'   => '',
                'active'  => '1'),
              'icon_mad.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_mad.gif' ,
                'alt'     => 'icon_mad',
                'short'   => ':-x',
                'alias'   => '',
                'active'  => '1'),
              'icon_razz.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_razz.gif' ,
                'alt'     => 'icon_razz',
                'short'   => ':-P',
                'alias'   => '',
                'active'  => '1'),
              'icon_redface.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_redface.gif' ,
                'alt'     => 'icon_redface',
                'short'   => ':oops:',
                'alias'   => '',
                'active'  => '1'),
              'icon_rolleyes.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_rolleyes.gif' ,
                'alt'     => 'icon_rolleyes',
                'short'   => ':roll:',
                'alias'   => '',
                'active'  => '1'),
              'icon_smile.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_smile.gif' ,
                'alt'     => 'icon_smile',
                'short'   => ':-)',
                'alias'   => '',
                'active'  => '1'),
              'icon_wink.gif' =>
              array('type'    => 0,
                'imgsrc'  => 'icon_wink.gif' ,
                'alt'     => 'icon_wink',
                'short'   => ';-)',
                'alias'   => '',
                'active'  => '1'));

        if($this->getVar('activate_auto') == 1) {
        $smiliepath_auto = DataUtil::formatForOS($this->getVar('smiliepath_auto'));
        $handle=opendir($smiliepath_auto);
        if($handle<>false) {
            while ($file = readdir($handle)) {
            if ($file != '.' && $file != '..' && $file != 'index.tpl'  && $file != 'CVS') {
                if(preg_match("/(.*?)(.gif|.jpg|.jpeg|.png)$/i", $file, $matches) <> 0) {
                $icons[$matches[1]] = array('type' => 1,
                        'imgsrc'  => $matches[0],
                        'alt'     => $matches[1],
                        'alias'   => '',
                        'short'   => ":" . $matches[1] . ":",
                        'active'  => '1');

                }
            }
            }
        }
        }
        return $icons;
    }

    /**
    * get available admin panel links
    *
    * @return array array of admin links
    */
    public function getlinks()
    {
        $links = array();
        if (SecurityUtil::checkPermission('BBSmile::', '::', ACCESS_ADMIN)) {
            $links[] = array(
                'url' => ModUtil::url('BBSmile', 'admin', 'main'),
                'text' => $this->__('Settings')
            );
            //if($this->getVar('activate_auto') == 1) {
                $links[] = array(
                    'url' => ModUtil::url('BBSmile', 'admin', 'readsmilies'),
                    'text' => $this->__('Read smilies out of the directory')
                );
                $links[] = array(
                    'url' => ModUtil::url('BBSmile', 'admin', 'editsmilies', array('aid' => -1)),
                    'text' => $this->__('Edit the defined smilies'),
                    'class' => 'z-icon-es-list'
                );
/*
            } else {
                $links[] = array(
                    'url' => ModUtil::url('BBSmile', 'admin', 'readsmilies'),
                    'text' => $this->__('Read smilies out of the directory'),
                    'title' => $this->__('Extended Smilies not yet activated!'),
                    'disabled' => true
                );
                $links[] = array(
                    'url' => ModUtil::url('BBSmile', 'admin', 'editsmilies', array('aid' => -1)),
                    'text' => $this->__('Edit the defined smilies'),
                    'title' => $this->__('Extended Smilies not yet activated!'),
                    'disabled' => true,
                    'class' => 'z-icon-es-list'
                );
*/
            //}
        }
        return $links;
    }
}