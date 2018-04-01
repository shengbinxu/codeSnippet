<?php

/**
 * 利用栈，对后缀表达式求值
 * 后缀表达式求值
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/4/1
 * Time: AM10:20
 */
class PostfixExpression
{
	public $stack;

	public function __construct($str)
	{
		$this->stack = new SplStack();
		for ($i = 0; $i < strlen($str); $i++) {
			$c = $str[$i];
			if ($c > '0' && $c < '10') {
				$this->stack->push($c);
			}
			if ($c == '*') {
				$this->stack->push(($this->stack->pop()) * ($this->stack->pop()));
			}
			if ($c == '+') {
				$this->stack->push(($this->stack->pop()) + ($this->stack->pop()));
			}
		}
	}

	public function __toString()
	{
		return (string)$this->stack->pop();
	}
}


$str = '5 9 8 + 4 6 * * 7 + *';
echo new PostfixExpression($str);