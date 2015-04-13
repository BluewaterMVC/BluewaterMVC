<?php

class OracleConnect{

    var $conn = false;

    var $connected_to_db = false;

	var $db = array(

            'engine'            => array( 'username'=>'ENGINE',
                                          'password'=>'ZACKS',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = niva)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = NI00.zacks.com)))',
                                          'error'=>'1'
                                        ),

            'customer'          => array( 'username'=>'CUSTOMER',
                                          'password'=>'ZACKS',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = niva)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = NI00.zacks.com)))',
                                          'error'=>'2'
                                        ),

            'product'           => array( 'username'=>'PRODUCT',
                                          'password'=>'ZACKS',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = niva)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = NI00.zacks.com)))',
                                          'error'=>'3'
                                        ),

            'data_holder'       => array( 'username'=>'DATA_HOLDER',
                                          'password'=>'ZACKS',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = niva)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = NI00.zacks.com)))',
                                          'error'=>'4'
                                        ),

            'lookup'            => array( 'username'=>'LOOKUP',
                                          'password'=>'ZACKS',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = niva)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = NI00.zacks.com)))',
                                          'error'=>'5'
                                        ),

            'app_prog'	        => array( 'username'=>'APP_PROG',
                                          'password'=>'ZACKS',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (COMMUNITY = tcp.world) (PROTOCOL = TCP) (HOST = news2.zacks.com) (PORT = 1521))) (CONNECT_DATA = (SID = NENZ01)))',
                                          'error'=>'6'
                                        ),

            'php_dev'           => array( 'username'=>'PHP_DEV',
                                          'password'=>'ZACKS',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = niva)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = NI00.zacks.com)))',
                                          'error'=>'7'
                                        ),

            'new_customer'	    => array( 'username'=>'INTERNET_CONNECT',
                                          'password'=>'INTER4YOU',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = niva)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = NI00.zacks.com)))',
                                          'error'=>'8'
                                        ),

            'portfolio_widget'  => array( 'username'=>'NEW_CUSTOMER',
                                          'password'=>'ZACKS',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = niva)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = NI00.zacks.com)))',
                                          'error'=>'8'
                                        ),

            'mutual_funds'      => array( 'username'=>'MUTUAL_FUND',
                                          'password'=>'ZACKS',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = niva)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = NI00.zacks.com)))'
                                        ),

            'comm'              => array( 'username'=>'COMM',
                                          'password'=>'ZACKS',
                                          'database'=>'(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = niva)(PORT = 1521))) (CONNECT_DATA = (SERVICE_NAME = NI00.zacks.com)))'
                                        )

                   );





	function OracleConnect($dbname, $show_error = true)
    {
        // ociinternaldebug(1);

        if( is_array($this->db[$dbname]) )
        {
            if (isset($this->db[$dbname]['conn']))
            {
                $this->conn = $this->db[$dbname]['conn'];
            } 
            else 
            {
                $this->conn = @OCILogon($this->db[$dbname]['username'], $this->db[$dbname]['password'], $this->db[$dbname]['database']);
                $this->db[$dbname]['conn'] = $this->conn;
            }

            if(is_array(OCIError()) && sizeof(OCIError()) > 0)
            {
                //echo '<pre>';
                //var_dump(OCIError());
                //echo '</pre>';
            }

            if(!$this->conn)
            {
                if($show_error){
                    echo '<!-- ZDBERMSG: '.$this->db[$dbname]['error'].'-->';
                    exit;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $this->connected_to_db = $dbname;
                return true;
            }
        }
        return false;
    }

	function close(){
		if( !empty($this->connected_to_db) && $this->conn)
			OCILogOff($this->conn);
	}
}

// eof
