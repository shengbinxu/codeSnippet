/*
 * Copyright (C) 2018 Baidu, Inc. All Rights Reserved.
 */
// function timeout(ms) {
//     return new Promise((resolve, reject) => {
//         setTimeout(resolve, ms, 'done');
//     });
// }
//
// timeout(2000).then((value) => {
//     console.log(value);
// });


function timeout(ms) {
    return new Promise(function (resolve, reject) {
        setTimeout(resolve, ms, 'done');
    });
}

timeout(2000).then(function (value) {
    console.log(value);
});

/*
 Promise构造函数接受一个函数作为参数，该函数的两个参数分别是resolve和reject。它们是两个函数，由 JavaScript 引擎提供，不用自己部署。

 */