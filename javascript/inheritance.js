/*
在基于类的语言中，对象是类的实例，并且类可以从另一个类中继承。Javascript是一门基于原型的语言，这意味着对象直接从其他对象继承。
 */

/*
当一个函数对象被创建时，Function构造器产生的函数对象会运行类似这样的一些代码：
this.prototype = {constructor: this}
新函数对象被赋予一个prototype属性，其值是包含一个constructor属性且属性值为该新函数对象。该prototype对象是存放继承特性的地方。
 */

Function.prototype.method = function (name, func) {
    this.prototype[name] = func;
    return this;
};

Function.method('inherits', function (parent) {
    this.prototype = new parent();
    return this;
});


var Mammal = function (name) {
    this.name = name;
};

Mammal.prototype.get_name = function () {
    return this.name;
};

Mammal.method('says', function () {
    return this.saying || '';
});

var myMammal = new Mammal('Hero the mammal');
// var name = myMammal.get_name();
document.writeln(name);

var Cat = function (name) {
    this.name = name;
    this.saying = 'meow';
}.inherits(Mammal)
    .method('get_name', function () {
        return this.says() + ' ' + this.name + ' ' + this.says();
    });
var myCat = new Cat('cat');
document.writeln('myCat----');
document.writeln(myCat.get_name());



