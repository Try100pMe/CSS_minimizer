<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.01.15
 * Time: 11:46
 */
//минимизатор, пока для CSS

class minimize{
    function minimize($folder, $output){
        $this->folder = ($folder ? $folder : 'myfolder'); //
        //$this->filetypes = array('css'=>true); // Acceptable file extensions to consider
        $this->output = ($output ? $output : 'mysprite'); // Output filenames, mysprite.png and mysprite.css
        $this->files = array();
    }

    function start(){
        //проверяем наличие файлов и расширения
       if($handle = opendir($this->folder)) {
            while (false !== ($file = readdir($handle))) {
                //$split = explode('.',$file);
                // Ignore non-matching file extensions
                //if($file[0] == '.' || !isset($this->filetypes[$split[count($split)-1]]))
                    //continue;
                $this->files[$file] = urlencode(@file_get_contents($this->folder.'/'.$file)); //название файла => содержимое

            }
            foreach($this->files as $file=>$text){
                $newName = str_replace('.css','.min.css',$file);
                @file_put_contents($this->output.'/'.$newName, $this->text_replace(urldecode($text)));
            }
            closedir($handle);
        }

        //$text='/*comment*/ #  orientation: portrait   img-o2d5,    .img-o2d6 { background-position: -18px -1459px; width: 18px; height: 27px; } .img-o2d5_left { background-position: -0px -1473px; width: 18px; height: 27px; } .img-o2d6 { background-position: -18px -1486px; width: 18px; height: 27px; } .img-o2d6_left { background-position: -0px -1500px; width: 18px; height: 27px; } .img-o2d7 { background-position: -18px -1513px; width: 18px; height: 27px; }';
        //echo $this->text_replace($text);

        //print_r($this->files);
    }
    function text_replace($text){
        //$text='/*comment*/ .img-o2d5, .img-o2d6 { background-position: -18px -1459px; width: 18px; height: 27px; } .img-o2d5_left { background-position: -0px -1473px; width: 18px; height: 27px; } .img-o2d6 { background-position: -18px -1486px; width: 18px; height: 27px; } .img-o2d6_left { background-position: -0px -1500px; width: 18px; height: 27px; } .img-o2d7 { background-position: -18px -1513px; width: 18px; height: 27px; }';
        $text=preg_replace('/\s{2,}/',' ',$text); //повторяющиеся пробелы
        $text=preg_replace('/\n+/','',$text);  //переносы строк
        $text=preg_replace('/\t+/','',$text);  //табуляция
        $text=preg_replace('/\s*\{\s*/', '{',$text); //пробелы рядом с синтаксическими символами
        $text=preg_replace('/\s*}\s*/', '}',$text);
        $text=preg_replace('/\s*\,\s*/', ',',$text);
        $text=preg_replace('/\s*:\s*/', ':',$text);
        $text=preg_replace('/\s*;\s*/', ';',$text);
        $text=preg_replace('/\s*\)\s*/', ')',$text);
        $text=preg_replace('#/\*(?:[^*]*(?:\*(?!/))*)*\*/#', '',$text); //удаление коментариев
        //echo $text;
        return $text;
    }
}
$minimize=new minimize('css', 'min'); //указать имя папки входящих и исходящих файлов
$minimize->start();
?>