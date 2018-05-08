<?php

namespace Infonesy\ZeroTalk;

class Post extends \B2\Obj
{
	function infonesy_uuid()
	{
		return sprintf('zeronet.%s.topic_%s.post_%s', $this->zero_id(), $this->topic_id(), $this->id());
	}

	function infonesy_type() { return 'Post'; }
	function infonesy_markup() { return 'Markdown'; }

	function text() { return $this->source(); }
}
