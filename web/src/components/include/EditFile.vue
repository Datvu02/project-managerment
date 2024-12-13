<template>
  <div class="pop-over is-shown" :style="{left: offset.left+'px', top: offset.top+'px'}">
    <div class="no-back">
      <div class="pop-over-header js-pop-over-header"><span class="pop-over-header-title">Sửa Tệp đính kèm</span><a
          href="#" class="pop-over-header-close-btn icon-sm icon-close" @click="closeFileEdit"><i class="el-icon-close"></i></a></div>
      <div>
        <div class="pop-over-content js-pop-over-content u-fancy-scrollbar js-tab-parent">
          <div>
            <div>
              <form>
                <label>Liên kết tên
                  <input class="attachment-name js-autofocus" type="text"
                                          v-model="title">
                </label>
                <input
                  class="edit-attachment wide nch-button nch-button--primary" style="margin: 0;"
                  value="Cập nhật" @click="handleEditName" type="submit">
                <div class="hide save-attachment-container"></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../../api";
export default {
  name: "EditFile",
  props: ['offset'],
  data() {
    return {
      title: '',
    }
  },
  methods:{
    handleEditName(){
      if (this.title != ""){
        let payload={
          name:this.title
        }
        api.updteFile(payload,this.offset.file.id).then(()=>{
          this.$emit('reload');
        })
      }

    },
    closeFileEdit(){
      this.$emit('closeFileEdit')
    }
  },
  mounted() {
    this.title = this.offset.file.name
  }
}
</script>

<style scoped lang="scss">
.pop-over {
  z-index: 9999;
  background: #fff;
  border-radius: 3px;
  box-shadow: 0 8px 16px -4px rgb(9 30 66 / 25%), 0 0 0 1px rgb(9 30 66 / 8%);
  overflow: hidden;
  position: absolute;
  width: 304px;

  .no-back {
    .pop-over-header {
      height: 40px;
      position: relative;
      margin-bottom: 8px;
      text-align: center;

      .pop-over-header-back-btn {
        color: #6b778c;
        padding: 10px 12px 10px 8px;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 2;
        height: 20px;
        font-size: 16px;
        line-height: 20px;
        width: 20px;
        display: inline-block;
        font-family: trellicons;
        font-style: normal;
        font-weight: 400;
        text-align: center;
        text-decoration: none;
        vertical-align: bottom;
        cursor: pointer;
      }

      .pop-over-header-title {
        box-sizing: border-box;
        color: #5e6c84;
        display: block;
        line-height: 40px;
        border-bottom: 1px solid rgba(9, 30, 66, .13);
        margin: 0 12px;
        overflow: hidden;
        padding: 0 32px;
        position: relative;
        text-overflow: ellipsis;
        white-space: nowrap;
        z-index: 1;
      }

      .pop-over-header-close-btn {
        color: #6b778c;
        padding: 10px 12px 10px 8px;
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        height: 20px;
        font-size: 16px;
        line-height: 20px;
        width: 20px;
        display: inline-block;
        font-family: trellicons;
        font-style: normal;
        font-weight: 400;
        text-align: center;
        text-decoration: none;
        vertical-align: bottom;
        cursor: pointer;
      }
    }

    div {
      .pop-over-content {
        max-height: 368px;
        overflow-x: hidden;
        overflow-y: auto;
        padding: 0 12px 12px;

        div {
          div {
            label{
              text-align: left;
              .attachment-name{
                margin: 4px 0 12px;
                width: 100%;
                outline: none;
                border: none;
                box-shadow: inset 0 0 0 2px #dfe1e6;
                background-color: #fafbfc;
                box-sizing: border-box;
                -webkit-appearance: none;
                border-radius: 3px;
                display: block;
                line-height: 20px;
                padding: 8px 12px;
                transition-property: background-color,border-color,box-shadow;
                transition-duration: 85ms;
                transition-timing-function: ease;
              }
            }


            .edit-attachment{
              background-color: #61BD4F;
              float: left;
              color: #fff;
              outline: none;
            }
          }
        }
      }

      /* width */
      .pop-over-content::-webkit-scrollbar {
        height: 8px;
        //width: 98%;
        width: 8px;
      }

      /* Track */
      .pop-over-content::-webkit-scrollbar-track {
        border-radius: 7px;
        background-color: #4f4f5073;
      }

      /* Handle */
      .pop-over-content::-webkit-scrollbar-thumb {
        background: #bcbcbe63;
        border-radius: 7px;
      }

      /* Handle on hover */
      .pop-over-content::-webkit-scrollbar-thumb:hover {
        background: #d3d3d87d;
      }
    }
  }
}
</style>