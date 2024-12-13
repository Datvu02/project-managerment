<template>
  <div class="pop-over is-shown" :style="{left: offset.left+'px', top: offset.top+'px'}" data-elevation="1">
    <div class="no-back">
      <div class="pop-over-header js-pop-over-header"><span class="pop-over-header-title">Thao tác</span>
        <a href="#"
           class="pop-over-header-close-btn icon-sm icon-close" @click="closeAction"><i class="el-icon-close"></i></a>
      </div>
      <div>
        <div class="pop-over-content js-pop-over-content u-fancy-scrollbar js-tab-parent" style="max-height: 475px;">
          <div>
            <div>
<!--              <ul class="pop-over-list">-->
<!--                <li><a class="js-add-card" href="#">Thêm thẻ...</a></li>-->
<!--              </ul>-->
              <ul class="pop-over-list">
                <li><a class="js-close-list remove-list" @click="deleteList" href="#">Xóa danh sách này!</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Action",
  props: ['offset'],
  methods:{
    deleteList(){
      this.$emit('deleteList',this.offset.id)
    },
    closeAction(){
      this.$emit('closeAction')
    },
    addList(){
      this.$emit('addList')
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
            .pop-over-list {
              list-style: none;
              margin: 0;
              padding: 0;
              text-align: left;

              li {
                display: list-item;
                text-align: -webkit-match-parent;

                a {
                  cursor: pointer;
                  display: block;
                  font-weight: 400;
                  padding: 6px 12px;
                  position: relative;
                  margin: 0 -12px;
                  text-decoration: none;
                }
                a:visited{
                  color: #172b4d;;
                }
                a:hover{
                  background-color: #F5F6F8;
                }
              }


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