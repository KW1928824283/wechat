<?php
    include_once './RecEvent.php';
    include_once './RecText.php';
	class Result 
	{
           
		public static  function getResult($postObj)
		{
			$RX_TYPE = trim($postObj->MsgType);

                  
                  //消息类型分离
                  switch ($RX_TYPE)
                  {
                      case "event":
                          $result = RecEvent::receiveEvent($postObj);
                          break;
                      case "text":
                         
                          $result = RecText::receiveText($postObj);
                          
                          break;
                      default:
                          exit;
                          break;
                  }
                  return $result;
		}

	}
?>