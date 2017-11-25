<?php
namespace ObserverPattern;

/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */
interface Observer{
    public function export($message);
}