/**
 * js原型继承：每个对象都连接到一个原型对象，并且可以从中继承属性。所有通过对象字面量创建的对象都连接到Object.prototype这个js中的标准对象。
 * 个人理解：
 * js中的对象，可以类比为java中的类。java中所有类都继承Object类
 * Object.prototype是原型对象，他是个对象。可以理解为：js中所有的对象，都继承这个原型对象。
 */

var animal = {
    name: "animal",
    voice: function () {
        return 'haha';
    }
};

/**
 * 可以修改一个对象的原型对象，即可以创建一个新对象，使他继承一个已有对象。
 */
if (typeof Object.beget !== 'function') {
    Object.beget = function (o) {
        var F = function () {};
        F.prototype = o;
        return new F();
        /**
         * 疑问：这里为啥不可以
         */
        // var F = {};
        // F.prototype = o;
        // return F;
    }
}
var dog = Object.beget(animal);

document.writeln(dog.name);
document.writeln(dog.voice());

dog.voice = function () {
    return 'wangwang';
};

document.writeln(dog.voice());


var myObject = {
    value: 1,
    /**
     * 方法调用模式：当一个函数被保存为一个对象的属性时，我们称它为一个方法。当一个方法被调用时，this被绑定到该对象。
     * @param inc
     */
    increment: function (inc) {
        this.value += typeof inc == 'number' ? inc : 1;
    },
    /**
     * this不是指代当前object，Javascript has no block scope, only function scope
     * https://stackoverflow.com/questions/7043509/this-inside-object
     */
    value2: this.value + 1,
    /**
     * error the myOject is not ready for that yet.
     */
    // value3: myObject.value + 1
};

myObject.increment();
document.writeln(myObject.value);
document.writeln(myObject.value2);
myObject.value3 = myObject.value + 1; // right
document.writeln(myObject.value3);

var add = function (a, b) {
    return a + b;
};
/**
 * 函数调用模式：this指向全局对象。
 */
document.writeln(add(3, 4));

/**
 * 构造器调用模式
 * 如果在一个函数前面带上new来调用，那么将创建一个隐藏连接到该函数的prototype成员的新对象。
 */
// Quo是一个函数，函数也是对象。
var Quo = function (string) {
    this.status = string;
};

// 给Quo的所有实例提供一个名为get_status的公共方法
Quo.prototype.get_status = function () {
    return this.status;
}

// 构造一个Quo实例
var myQuo = new Quo('confused');
document.writeln(myQuo.get_status());

/**
 * apply调用模式
 */



