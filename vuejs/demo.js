/**
 *
 * Created by xushengbin on 2018/1/13.
 */

var app = new Vue({
	el: '#app',
	data: {
		message: '<span style="color: red;">hello</span>',
		message: 'hello world!',
		id: 'ddd'
	}
})

var app2 = new Vue({
	el: '#app-2',
	data: {
		message: '页面加载于 ' + new Date().toLocaleString(),
		seen:true
	}
})
Vue.component('todo-list',{
	props : ['todo'],
	template : '<li>{{todo.name}}</li>'
});
var app3 = new Vue({
	el : '#app-3',
	data : {
		list : [
			{name: 'xushengbin'},
			{name: 'xushengbin2'},
			{name: 'xushengbin3'},
			{name: 'xushengbin4'},
		],
		'isButtonDisabled' : false
	},
	methods: {
		add : function () {
			this.list.push({name:'zhuqiaozhen'})
		}
	}
})

var app4 = new Vue({
	el : '#app-4',
	data : {
		message : ''
	}
})



var app5 = new Vue({
	el : '#app-5'
})