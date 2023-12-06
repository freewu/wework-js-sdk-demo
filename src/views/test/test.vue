<script lang="ts" setup>
import { reactive, ref } from "vue"
import type { FormInstance, FormRules } from 'element-plus'
import sha1 from 'crypto-js/sha1';

const getTimestamp = () => {
    return ((new Date()).getTime() / 1000).toFixed(0);
}

const sign = (ticket,str,timestamp,url) => {
    let s = "jsapi_ticket=" + ticket + "&noncestr="+str+"&timestamp="+timestamp+"&url=" + url;
    console.log(s);
    return sha1(s).toString();
}


const error = ref("");

interface RuleForm {
  corpid: string
  appid: string
  ticket: string
  agent_ticket: string
  chatid: string
  text: string
  desc: string
  url: string
}

const ruleFormRef = ref<FormInstance>()
const form = reactive<RuleForm>({
  corpid: 'wwc035e6d0f9d075a1', // 企业微信的corpID
  appid: '1000002',  // 应用ID
  ticket: '', // 需要使用的 ticket 用于生成签名 signature
  agent_ticket: '', // 使用 agentConfig 需要的 ticket
  chatid: '', // 需要发送消息的外部群 ID
  text: '', // 需要发送的消息内容
  url: 'http://163.com', // 发送的链接
  desc: '' // 描述信息
})

const rules = reactive<FormRules<RuleForm>>({
  corpid: [
    { required: true, message: '不能为空', trigger: 'blur' },
  ],
  appid: [
    { required: true, message: '不能为空', trigger: 'blur' },
  ],
  ticket: [
    { required: true, message: '不能为空', trigger: 'blur' },
  ],
  agent_ticket: [
    { required: true, message: '不能为空', trigger: 'blur' },
  ],
  chatid: [
    { required: true, message: '不能为空', trigger: 'blur' },
  ],
  text: [
    { required: true, message: '不能为空', trigger: 'blur' },
  ],
  desc: [
    { required: true, message: '不能为空', trigger: 'blur' },
  ],
})

 console.log("aaaaaaaaaaaaa");

const submitForm = async (formEl: FormInstance | undefined) => {
    console.log("submitForm");
  if (!formEl) return
  await formEl.validate((valid, fields) => {
    console.log("validate:" + valid);
    if (valid) {
        //alert("aaaa");
        //console.log("aaaa");
        //error.value = "2222";
        doooo(form);
    } else {
      console.log('error submit!', fields)
    }
  })
}


const doooo = (data) => {
    let timestamp = getTimestamp();
    let str = "abc";
    let url = window.location.origin;
    let ss = sign(data.ticket,str,timestamp,url);
    console.log(ss);
    //return ;

    //console.log(sign(data.ticket,str,timestamp,url));

    // 通过config接口注入权限验证配置
    // 所有需要使用JS-SDK的页面必须先注入配置信息，
    // 否则将无法调用（同一个url仅需调用一次，对于变化url的SPA（single-page application）的web app可在每次url变化时进行调用）
    wx.config({
        beta: true,// 必须这么写，否则wx.invoke调用形式的jsapi会有问题
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: data.corpid, // 必填，企业微信的corpID，必须是本企业的corpID，不允许跨企业使用
        timestamp: timestamp, // 必填，生成签名的时间戳
        nonceStr: str, // 必填，生成签名的随机串
        signature: ss,// 必填，签名，见 附录-JS-SDK使用权限签名算法
        jsApiList: [
            'agentConfig',
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getLocalImgData',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ], // 必填，需要使用的JS接口列表，凡是要调用的接口都需要传进来
        success: function(res) {
            console.log("wx.config success");
            console.log(res);
        },
        fail: function(res) {
            console.log("wx.config fail");
            console.log(res);
            error.value = res;
        }
    });

    // 通过error接口处理失败验证
    wx.error(function(res) {
        console.log("wx.error");
        console.log(res);
        error.value = res;
        // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
    });

    // 通过ready接口处理成功验证
    wx.ready(function() {
        //let url = location.href;
        console.log("wx.ready");
        // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，
        /// config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。
        // 对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
        
        //alert(wx.agentConfig);
        // console.log(wx.agentConfig);
        if (wx.agentConfig == null) {
            error.value = "不存在 wx.agentConfig 方法";
            return;
        }
        let ss2 = sign(data.agent_ticket,str,timestamp,url + "/");
        console.log(ss2);
        wx.agentConfig({
            corpid: data.corpid, // 必填，企业微信的corpid，必须与当前登录的企业一致
            agentid: data.appid, // 必填，企业微信的应用id （e.g. 1000247）
            timestamp: timestamp, // 必填，生成签名的时间戳
            nonceStr: str, // 必填，生成签名的随机串
            signature: ss2,// 必填，签名，见附录-JS-SDK使用权限签名算法
            jsApiList: ['selectExternalContact','shareToExternalChat','openExistedChatWithMsg'], //必填，传入需要使用的接口名称
            success: function(res) {
                console.log("wx.agentConfig success");
                console.log(res);
                wx.invoke("openExistedChatWithMsg", {
                    chatId: data.chatid,
                    msg: {
                      msgtype: "link",
                      link: {
                          title: data.text,
                          desc: data.desc,
                          url: data.url,
                          //imgUrl: "imgurl1"
                      }
                    }
                  },function(res) {
                        if (res.err_msg == "openExistedChatWithMsg:ok")
                        {
                        }
                        console.log(res);
                        error.value = res.err_msg;
                  }
                );
                // wx.invoke("shareToExternalChat", {
                //     chatIds: [ data.chatid ], //客户群ID，从4.1.10版本开始支持（mac端不支持）
                //     text: {
                //         content: data.text,    // 文本内容
                //     },
                //     attachments: [
                //             // {
                //             //     msgtype: "image",    // 消息类型，必填
                //             //     image: {
                //             //             mediaid: "",      // 图片的素材id
                //             //             imgUrl: "",        // 图片的imgUrl,跟图片mediaid填其中一个即可
                //             //     },
                //             // },
                //             // {
                //             //     msgtype: "link",    // 消息类型，必填
                //             //     link: {
                //             //             title: "",        // H5消息标题
                //             //             imgUrl: "",    // H5消息封面图片URL
                //             //             desc: "",    // H5消息摘要
                //             //             url: "",        // H5消息页面url 必填
                //             //     },
                //             // },
                //             // {
                //             //     msgtype: "miniprogram",    // 消息类型，必填
                //             //     miniprogram: {
                //             //             appid: "",    // 小程序的appid
                //             //             title: "",        // 小程序消息的title
                //             //             imgUrl : "",    //小程序消息的封面图。必须带http或者https协议头
                //             //             page: "",        //小程序消息打开后的路径，注意要以.html作为后缀，否则在微信端打开会提示找不到页面
                //             //     },
                //             // },
                //             // {
                //             //     msgtype: "video",    // 消息类型，必填
                //             //     video:{
                //             //             mediaid:"",        // 视频的素材id
                //             //     },
                //             // },
                //             // {
                //             //     msgtype: "file",    // 消息类型，必填，从3.1.12版本开始支持
                //             //     file:{
                //             //             mediaid:"",        // 文件的素材id，必填
                //             //     },
                //             // },
                //     ]},function(res) {
                //         if (res.err_msg == "shareToExternalChat:ok") {
                //             error.value = "shareToExternalChat:ok";
                //         }
                //         console.log(res);
                //     }
                //);
            },
            fail: function(res) {
                console.log("wx.agentConfig fail");
                console.log(res);
                if(res.errMsg.indexOf('function not exist') > -1) {
                    console.log('版本过低请升级')
                }
                error.value = res;
            }
        });
    });

    // // 通过error接口处理失败验证
    // wx.error(function(res) {
    //     error.value(res);
    //     alert(JSON.stringify(res));
    //     // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
    // });
}



// // 通过config接口注入权限验证配置
// // 所有需要使用JS-SDK的页面必须先注入配置信息，
// // 否则将无法调用（同一个url仅需调用一次，对于变化url的SPA（single-page application）的web app可在每次url变化时进行调用）

// wx.config({
//     beta: true,// 必须这么写，否则wx.invoke调用形式的jsapi会有问题
//     debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
//     appId: '', // 必填，企业微信的corpID，必须是本企业的corpID，不允许跨企业使用
//     timestamp: '', // 必填，生成签名的时间戳
//     nonceStr: '', // 必填，生成签名的随机串
//     signature: '',// 必填，签名，见 附录-JS-SDK使用权限签名算法
//     jsApiList: [
//         'agentConfig',
//         'checkJsApi',
//         'onMenuShareTimeline',
//         'onMenuShareAppMessage',
//         'onMenuShareQQ',
//         'onMenuShareWeibo',
//         'hideMenuItems',
//         'showMenuItems',
//         'hideAllNonBaseMenuItem',
//         'showAllNonBaseMenuItem',
//         'chooseImage',
//         'previewImage',
//         'uploadImage',
//         'downloadImage',
//         'getLocalImgData',
//         'getNetworkType',
//         'openLocation',
//         'getLocation',
//         'hideOptionMenu',
//         'showOptionMenu',
//         'closeWindow',
//         'scanQRCode',
//         'chooseWXPay',
//         'openProductSpecificView',
//         'addCard',
//         'chooseCard',
//         'openCard'
//     ] // 必填，需要使用的JS接口列表，凡是要调用的接口都需要传进来
// });

// // 通过ready接口处理成功验证
// wx.ready(function() {
//     // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，
//     /// config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。
//     // 对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
    
//     const t = () => {
//         alert(wx.agentConfig);
//         console.log(wx.agentConfig);
//         if (wx.agentConfig == null) {
//             error.value = "不存在 wx.agentConfig 方法";
//             return;
//         }
//         wx.agentConfig({
//             corpid: '', // 必填，企业微信的corpid，必须与当前登录的企业一致
//             agentid: '', // 必填，企业微信的应用id （e.g. 1000247）
//             timestamp: '', // 必填，生成签名的时间戳
//             nonceStr: '', // 必填，生成签名的随机串
//             signature: '',// 必填，签名，见附录-JS-SDK使用权限签名算法
//             jsApiList: ['selectExternalContact'], //必填，传入需要使用的接口名称
//             success: function(res) {
//                 alert(res);
//                 console.log(res);
//                 // 回调
//                 wx.invoke('selectExternalContact',
//                     {
//                         "filterType": 0, // 0表示展示全部外部联系人列表，1表示仅展示未曾选择过的外部联系人。默认值为0；除了0与1，其他值非法。在企业微信2.4.22及以后版本支持该参数
//                     }, 
//                     function(res) {
//                         alert(res);
//                         if (res.err_msg == "selectExternalContact:ok") {
//                             userIds  = res.userIds ; //返回此次选择的外部联系人userId列表，数组类型
//                         } else {
//                             //错误处理
//                         }
//                     }
//                 );
//             },
//             fail: function(res) {
//                 alert(res);
//                 error.value = res;
//                 if(res.errMsg.indexOf('function not exist') > -1) {
//                     alert('版本过低请升级')
//                 }
//                 error.value = res;
//             }
//         });
//     }
//     t();
// });

// // 通过error接口处理失败验证
// wx.error(function(res){
//     error.value(res);
//     alert(JSON.stringify(res));
//     // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
// });


//t();

// console.log(wx);
</script>

<template>
  <div class="base-container">
 测试页aaaaa2052 {{ error }}
  <el-form ref="ruleFormRef" :model="form" label-width="120px" :rules="rules">
  <!--  corpid: '', // 企业微信的corpID
  appid: '',  // 应用ID
  ticket: '', // 需要使用的 ticket 用于生成签名 signature
  chatid: '', // 需要发送消息的外部群 ID
  text: '', // 需要发送的消息内容-->
    <el-form-item label="corpid" prop="corpid">
      <el-input v-model="form.corpid" clearable placeholder="企业微信的corpID"/>
      <br/>
      <a href="https://work.weixin.qq.com/wework_admin/frame#profile" target="_blank">https://work.weixin.qq.com/wework_admin/frame#profile</a>
    </el-form-item>
    <el-form-item label="appid" prop="appid">
      <el-input v-model="form.appid" clearable placeholder="自定义应用ID"/>
      <br/>
      <a href="https://work.weixin.qq.com/wework_admin/frame#apps" target="_blank">https://work.weixin.qq.com/wework_admin/frame#apps</a>
    </el-form-item>
    <el-form-item label="ticket" prop="ticket">
      <el-input v-model="form.ticket" clearable placeholder="wx.config 需要使用的 ticket 用于生成签名"/>
      <br/>
      <a href="https://work.jiaju-online.cn/server/weworkapi/api/examples/JsApiTest.php" target="_blank">https://work.jiaju-online.cn/server/weworkapi/api/examples/JsApiTest.php</a>
    </el-form-item>
        <el-form-item label="agent_ticket" prop="agent_ticket">
      <el-input v-model="form.agent_ticket" clearable placeholder="wx.agentConfig 需要使用的 ticket 用于生成签名"/>
      <br/>
      <a href="https://work.jiaju-online.cn/server/weworkapi/api/examples/agent-ticket.php" target="_blank">https://work.jiaju-online.cn/server/weworkapi/api/examples/agent-ticket.php</a>
    </el-form-item>
    <el-form-item label="chatid"  prop="chatid">
      <el-input v-model="form.chatid" clearable placeholder="需要发送消息的外部群"/>
      <br/>
      <a href="https://work.jiaju-online.cn/server/weworkapi/api/examples/group-chat.php" target="_blank">https://work.jiaju-online.cn/server/weworkapi/api/examples/group-chat.php</a>
    </el-form-item>
    <el-form-item label="发送的内容"  prop="text">
      <el-input v-model="form.text" type="textarea" />
    </el-form-item>
    <el-form-item label="描述信息"  prop="desc">
      <el-input v-model="form.desc" type="textarea" />
    </el-form-item>
    <el-form-item label="url"  prop="url">
      <el-input v-model="form.url" clearable placeholder="需要发送的链接地址"/>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="submitForm(ruleFormRef)">发送</el-button>
    </el-form-item>
  </el-form>

        <!-- 测试页aaaaa {{ error }}
        <el-button @click="buttonClick">测试一下</el-button> -->
  </div>
</template>

<style lang="scss" scoped>

</style>
