<template>
  <div class="pop-over is-shown" data-elevation="2" :style="{left: offset.left+'px', top: offset.top+'px'}">
    <div class="no-back">
      <div class="pop-over-header js-pop-over-header"><span
          class="pop-over-header-title">Bạn muốn xoá?</span><a href="#" @click="closeDelete"
                                                               class="pop-over-header-close-btn icon-sm icon-close"><i
          class="el-icon-close"></i></a>
      </div>
      <div>
        <div class="pop-over-content js-pop-over-content u-fancy-scrollbar js-tab-parent" style="max-height: 361px;">
          <div>
            <div><p>Dữ liệu không thể khôi phục.</p><input
                class="btn-confirm full nch-button nch-button--danger" type="submit" @click="handleDelete" value="Xoá"
                data-test-id=""></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '../../api'

export default {
  name: "Delete",
  props: ['offset'],
  methods: {
    handleDelete() {
      if (this.offset.type === "file") {
        api.deleteFile(this.offset.data.id).then(() => {
          this.$emit('closeDelete')
          this.$emit('reloadCard')
        })
      } else if (this.offset.type === "check_list"){
        api.deleteChecklist(this.offset.data.id).then(()=>{
          this.$emit('closeDelete')
          this.$emit('reloadCard')
        })
      }
      else if (this.offset.type === "card"){
        api.deleteCard(this.offset.id).then(()=>{
          this.$emit('closeDelete')
          this.$emit('reloadList')
        })
      } else if (this.offset.type === "list"){
        api.deleteList(this.offset.id).then(()=>{
          this.$emit('closeDelete')
          this.$emit('reloadList')
        })
      }
    },
    closeDelete() {
      this.$emit('closeDelete')
    }
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
    text-align: left;
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


            .btn-confirm {
              background-color: #EB5A46;
              width: 100%;
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