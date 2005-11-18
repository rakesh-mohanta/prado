<?php

class TErrorHandler extends TComponent implements IModule
{
	/**
	 * @var string module ID
	 */
	private $_id;
	/**
	 * @var TApplication application instance
	 */
	private $_application;

	/**
	 * Initializes the module.
	 * This method is required by IModule and is invoked by application.
	 * @param TApplication application
	 * @param TXmlElement module configuration
	 */
	public function init($application,$config)
	{
		$this->_application=$application;
		$application->attachEventHandler('Error',array($this,'handleError'));
		$application->setErrorHandler($this);
	}

	/**
	 * @return string id of this module
	 */
	public function getID()
	{
		return $this->_id;
	}

	/**
	 * @param string id of this module
	 */
	public function setID($value)
	{
		$this->_id=$value;
	}

	public function handleError($sender,$param)
	{
		if(($response=Prado::getApplication()->getResponse())!==null)
			$response->clear();
		switch(Prado::getApplication()->getMode())
		{
			case 'Off':
			case 'Debug':
				$this->displayException($param);
				exit(1);
			case 'Normal':
			case 'Performance':
				error_log($param->__toString());
				header("HTTP/1.0 500 Internal Error");
				exit(1);
		}
	}

	protected function displayException($exception)
	{
		$lines=file($exception->getFile());
		$errorLine=$exception->getLine();
		$beginLine=$errorLine-9>=0?$errorLine-9:0;
		$endLine=$errorLine+8<=count($lines)?$errorLine+8:count($lines);
		$source='';
		for($i=$beginLine;$i<$endLine;++$i)
		{
			if($i===$errorLine-1)
			{
				$line=highlight_string(sprintf("Line %4d: %s",$i+1,$lines[$i]),true);
				$source.="<div style=\"background-color: #ffeeee\">".$line."</div>";
			}
			else
				$source.=highlight_string(sprintf("Line %4d: %s",$i+1,$lines[$i]),true);
		}
		$fields=array(
			'%%ErrorType%%',
			'%%ErrorMessage%%',
			'%%SourceFile%%',
			'%%SourceCode%%',
			'%%StackTrace%%',
			'%%Version%%',
			'%%Time%%'
		);
		$values=array(
			get_class($exception),
			htmlspecialchars($exception->getMessage()),
			htmlspecialchars($exception->getFile()).' ('.$exception->getLine().')',
			$source,
			htmlspecialchars($exception->getTraceAsString()),
			$_SERVER['SERVER_SOFTWARE'].' <a href="http://www.pradosoft.com/">PRADO</a>/'.Prado::getVersion(),
			strftime('%Y-%m-%d %H:%m',time())
		);
		$languages=Prado::getUserLanguages();
		$errorFile=dirname(__FILE__).'/error.'.$languages[0];
		if(!is_file($errorFile))
			$errorFile=dirname(__FILE__).'/error.en';
		if(($content=@file_get_contents($errorFile))===false)
			die("Unable to open error template file '$errorFile'.");
		echo str_replace($fields,$values,$content);
	}
}

?>