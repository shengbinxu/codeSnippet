<?php
namespace ObserverPattern;
/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */
interface Subject{
    public function registerObserver(Observer $o);
    public function removeObserver(Observer $o);
    public function nofityObserver();
}