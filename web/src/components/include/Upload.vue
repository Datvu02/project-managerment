<template>
  <div>
    <div class="upload" @click="chooseImage">
      <input type="file" accept="image/*" id="upload" @change="uploadImage">
      <img v-if="url_image.length>0" :src="url_image" alt="">
      <img v-else src="../../assets/image/avatar.jpg" alt="">

    </div>
    <div class="icon_close" >
      <i class="el-icon-close" @click="removeImage"></i>
    </div>
  </div>
</template>

<script>
export default {
  name: "Upload",
  props:[
    'url'
  ],
  data (){
    return {
      base_url:'http://127.0.0.1:8000/storage/users/',
      url_default:'',
      url_image:'',
      file_img:''
    }
  },
  methods:{
    removeImage(){
      this.file_img = null;
      this.url_image = this.url_default
      this.$emit('removeImage')
      this.$emit('changeImage',this.file_img)
    },
    chooseImage(){
      document.getElementById('upload').click()
    },
    uploadImage(e){
      if(e.target.files.length){
        this.file_img = e.target.files[0]
        this.url_image = URL.createObjectURL(this.file_img);
        this.$emit('changeImage',this.file_img)
      }
    },

  },
  mounted() {
    if (this.url == null || this.url.length == 0){
      this.url_image=this.url_default
    }else{
      this.url_image = this.base_url+this.url
    }
  },
  watch:{
    url(){
      if (this.url == null || this.url.length == 0){
        this.url_image=this.url_default
      }else{
        this.url_image = this.base_url+this.url
      }
    }
  }
}
</script>

<style scoped lang="scss">
.upload{
  width: 100%;
  height: 400px;
  border: 1px solid #c3c3c3;
  border-radius: 7px;
  cursor: pointer;
  position: relative;
  input{
    opacity: 0;
    width: 0px;
    height: 0px;
  }
  img{
    height: 100px;
    width: 100px;
    line-height: 100px;
    position: absolute;
    border-radius: 7px;
  }
}
.upload:hover + .icon_close{
  display: block;
}
.icon_close:hover{
  display: block;
}
.icon_close{
  display: none;
  cursor: pointer;
  i{
    position: absolute;
    top: 30px;
    left: 400px;
  }
}
</style>