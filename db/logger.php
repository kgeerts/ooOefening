<?php


 class Logger
 {
    const verbose = 'echo';
    static public function log($msg='test') {
            $bt = debug_backtrace();
            $caller = array_shift($bt);
             
            if (self::verbose == "echo"){
                $file= $caller ['file'];
                $line= $caller ['line'];
                echo ('logger: '.$msg.' file: '.$file.' line: '.$line.'</br>' );
         }
    }
    static public function dumprequest ($param){
            $bt = debug_backtrace();
            $caller = array_shift($bt);
            
            if (self::verbose == "echo"){
                $file= $caller ['file'];
                $line= $caller ['line'];
                $msg ='logger: file: '.$file.' line: '.$line.'<br/>';
                echo ($msg );
         
                print('<pre>'); 
                    print_r($param); 
                print('</pre>');
         } 
    }
 }
?>
