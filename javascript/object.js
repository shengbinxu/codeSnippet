/*
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */
//创建一个名为Que的构造函数，他构造一个带有status属性的对象
var Que = function (string) {
    this.status = string;
}
//给Que的所有实例提供一个名为get_status的公共方法
Que.prototype.get_status = function () {
    return this.status;
}

var myQue = new Que('confused');
document.writeln(myQue.get_status());

var sum = function () {
    var i, sum = 0;
    for (i = 0; i < arguments.length; i++) {
        if (typeof arguments[i] !== 'number') {
            throw {
                name: 'TypeError',
                message: 'add needs numbers',
            }
        }
        sum += arguments[i];
    }
    return sum;
}
try {
    document.writeln(sum(4, 8, 16, 23, 42));
} catch (e) {
    document.writeln(e.name + ':' + e.message);
}

Function.prototype.method = function (name, func) {
    if (!this.prototype[name]) {
        this.prototype[name] = func;
        return this;
    }
}
//取一个数字的整数部分
Number.method('integer', function () {
    //Math.ceil(x) 返回大于等于x,且与x最接近的整数
    //Math.float(x) 返回小于等于x,且与x最接近的整数
    return Math[this < 0 ? 'ceil' : 'floor'](this);
})
document.writeln((-10 / 3).integer());
document.writeln((11 / 3).integer())
String.method('trim', function () {
    return this.replace(/^\s+|\s+$/g, '');
})
document.writeln("    neat   ".trim());

//递归
var walk_the_DOM = function walk(node, func) {
    func(node);
    node = node.firstChild;
    while (node) {
        walk(node,func);
        node = node.nextSibling;
    }
};
var getElementsByAttribute = function (att,value) {
    var results = [];
    walk_the_DOM(document.body,function (node) {
        var actual = node.nodeType === 1 && node.getAttribute(att);
        if(typeof actual === 'string' && (actual === value || typeof value !== 'string')) {
            results.push(node);
        }
    });
    return results;
};
console.log(getElementsByAttribute('class','class1'));

//闭包
//糟糕的例子
var add_the_handers = function (nodes) {
    var i ;
    for(i = 0; i < nodes.length; i++) {
        nodes[i].onclick = function (e) {
            alert(i); // 所有的点击事件i 都是 i * n
        }
    }
}
// add_the_handers(document.getElementsByClassName('class1'));
//正确的做法
var add_the_handers2 = function (nodes) {
    var i ;
    for(i = 0; i < nodes.length; i++) {
        nodes[i].onclick = function (i) {
            return function (e) {
                alert(i);
            }
        }(i);
    }
}
add_the_handers2(document.getElementsByClassName('class1'));

//寻找html中的字符串实体，并把他转换为对应的字符
String.method('deentityify',function () {
    var entity = {
        quot: '"',
        lt : '<',
        gt : '>',
    };
    return function () {
        return this.replace(/&([^&;]+);/g,function (a,b) {
            var r = entity[b];
            return typeof r === 'sting' ? r : a;
        })
    }
}());
document.writeln('&lt;&quot;&gt;'.deentityify())