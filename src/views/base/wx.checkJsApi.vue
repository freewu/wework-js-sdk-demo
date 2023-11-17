<script lang="ts" setup>
// import { reactive, ref } from "vue"
// import { useRouter } from "vue-router"
// import { useUserStore } from "@/store/modules/user"
// import { type FormInstance, type FormRules } from "element-plus"
// import { User, Lock, Key, Picture, Loading } from "@element-plus/icons-vue"
// import { getLoginCodeApi } from "@/api/login"
// import { type LoginRequestData } from "@/api/login/types/login"
// import ThemeSwitch from "@/components/ThemeSwitch/index.vue"

// const router = useRouter()

// /** 登录表单元素的引用 */
// const loginFormRef = ref<FormInstance | null>(null)

// /** 登录按钮 Loading */
// const loading = ref(false)
// /** 验证码图片 URL */
// const codeUrl = ref("")
// /** 登录表单数据 */
// const loginFormData: LoginRequestData = reactive({
//   username: "admin",
//   password: "12345678",
//   code: ""
// })
// /** 登录表单校验规则 */
// const loginFormRules: FormRules = {
//   username: [{ required: true, message: "请输入用户名", trigger: "blur" }],
//   password: [
//     { required: true, message: "请输入密码", trigger: "blur" },
//     { min: 8, max: 16, message: "长度在 8 到 16 个字符", trigger: "blur" }
//   ],
//   code: [{ required: true, message: "请输入验证码", trigger: "blur" }]
// }
// /** 登录逻辑 */
// const handleLogin = () => {
//   loginFormRef.value?.validate((valid: boolean, fields) => {
//     if (valid) {
//       loading.value = true
//       useUserStore()
//         .login(loginFormData)
//         .then(() => {
//           router.push({ path: "/" })
//         })
//         .catch(() => {
//           createCode()
//           loginFormData.password = ""
//         })
//         .finally(() => {
//           loading.value = false
//         })
//     } else {
//       console.error("表单校验不通过", fields)
//     }
//   })
// }
// /** 创建验证码 */
// const createCode = () => {
//   // 先清空验证码的输入
//   loginFormData.code = ""
//   // 获取验证码
//   codeUrl.value = ""
//   getLoginCodeApi().then((res) => {
//     codeUrl.value = res.data
//   })
// }

// /** 初始化验证码 */
// createCode()

console.log(wx);
</script>

<template>
  <div class="base-container">
        wx.checkJsApi
  </div>
</template>

<style lang="scss" scoped>

</style>
