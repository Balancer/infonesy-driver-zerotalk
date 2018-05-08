<?php

namespace Infonesy;

class ZeroTalk extends \B2\Obj
{
	function zerotalk_dir() { return $this->id(); }

	function infonesy_uuid()
	{
		return 'zeronet.'.$this->id();
	}

//	function can_be_empty() { return false; }

	function b2_configure($params=NULL)
	{
		parent::b2_configure($params);

		if(!preg_match('!/data/(\w{33,34})$!', $this->zerotalk_dir(), $m))
			\B2\Exception::throw("Unknown ZeroID in zerotalk_dir '%s'", $this->zerotalk_dir());

		$this->zerotalk_id = $m[1];

		$data_file = $this->zerotalk_dir().'/content.json';
		if(!file_exists($data_file))
			\B2\Exception::throw("Not exists file %s", $data_file);

		$data = json_decode(file_get_contents($data_file));

		$this->set('title', $data->title);
		$this->set('modify_time',  $data->modified);
	}

	function load_user_posts($user_id)
	{
		$user_data_file = $this->zerotalk_dir().'/data/users/'.$user_id.'/data.json';
		if(!file_exists($user_data_file))
			\B2\Exception::throw("Not exists file %s", $user_data_file);

		$user_data = json_decode(file_get_contents($user_data_file));

		$result = [];
		foreach($user_data->topic as $d)
		{
			$x = new ZeroTalk\Post('t'.$d->topic_id);
//			$x->set('blog', $blog);
			$x->set('zero_id', $this->zerotalk_id);
//			$x->set('title', $d->title);
			$x->set('create_time', round($d->added));

			$text = @$d->body;

			$text = preg_replace("!\]\(data/img/post!", '](https://www.zerogate.tk/'.$this->zerotalk_id.'/data/img/post', $text);
			$text = preg_replace("!\]\((/\w{33,34}/)!", '](https://www.zerogate.tk$1', $text);
			$text = str_replace('http://127.0.0.1:43110/', 'https://www.zerogate.tk/', $text);
			$text = preg_replace('!(^| )(https?://\S+?)( |$)!m', '$1<$2>$3', $text);
			$text = preg_replace("! src=\"(/\w{34}/[^\"]+?\.(mp4))\"!", ' src="https://www.zerogate.tk$1"', $text);
			$text = preg_replace("! src=\"cors-(\w{34}/[^\"]+?\.(mp4))\"!", ' src="https://www.zerogate.tk/$1"', $text);
			$text = preg_replace("! src=\"(data/[^\"]+?\.(mp4))\"!", ' src="https://www.zerogate.tk/'.$this->zerotalk_id.'/$1"', $text);

			$text = preg_replace_callback("!(<video .*?</video>)!s", function($m) {
				return str_replace("\n", "", $m[1]);
			}, $text);

			$x->set('source', $text);

//			$x->set('infonesy_container', $blog);
//			$x->set('infonesy_node', $blog);

			$result[] = $x;
		}

		foreach($user_data->comment as $topic_id => $posts)
		{
			foreach($posts as $d)
			{
				$x = new ZeroTalk\Post($d->comment_id);

//				$x->set('blog', $blog);
				$x->set('zero_id', $this->zerotalk_id);
//				$x->set('title', $d->title);
				$x->set('create_time', round($d->added));

				$text = @$d->body;

				$text = preg_replace("!\]\(data/img/post!", '](https://www.zerogate.tk/'.$this->zerotalk_id.'/data/img/post', $text);
				$text = preg_replace("!\]\((/\w{33,34}/)!", '](https://www.zerogate.tk$1', $text);
				$text = str_replace('http://127.0.0.1:43110/', 'https://www.zerogate.tk/', $text);
				$text = preg_replace('!(^| )(https?://\S+?)( |$)!m', '$1<$2>$3', $text);
				$text = preg_replace("! src=\"(/\w{34}/[^\"]+?\.(mp4))\"!", ' src="https://www.zerogate.tk$1"', $text);
				$text = preg_replace("! src=\"cors-(\w{34}/[^\"]+?\.(mp4))\"!", ' src="https://www.zerogate.tk/$1"', $text);
				$text = preg_replace("! src=\"(data/[^\"]+?\.(mp4))\"!", ' src="https://www.zerogate.tk/'.$this->zerotalk_id.'/$1"', $text);

				$text = preg_replace_callback("!(<video .*?</video>)!s", function($m) {
					return str_replace("\n", "", $m[1]);
				}, $text);

				$x->set('source', $text);

//				$x->set('infonesy_container', $blog);
//				$x->set('infonesy_node', $blog);

				$result[] = $x;
			}
		}

		return $result;
	}
}
