<?php

namespace Infonesy\ZeroTalk;

class Topic extends \B2\Obj
{
	function infonesy_uuid()
	{
		return 'zeronet.'.$this->zero_id().'.topic_'.$this->id();
	}

	function infonesy_type() { return 'Topic'; }
	function infonesy_markup() { return 'Markdown'; }
}
