<template>
  <div class="pop-over is-shown" data-elevation="1" :style="{left: offset.left+'px', top: offset.top+'px'}">
    <div class="no-back">
      <div class="pop-over-header js-pop-over-header"><span class="pop-over-header-title">Di chuyển thẻ</span><a
          href="#" class="pop-over-header-close-btn icon-sm icon-close" @click="closeMove"><i
          class="el-icon-close"></i></a></div>
      <div>
        <div class="pop-over-content js-pop-over-content u-fancy-scrollbar js-tab-parent" style="max-height: 395px;">
          <div>
            <div>
              <div class="pop-over-section"><h4>Chọn đích đến</h4>
                <div class="form-grid">
                  <div class="button-link setting form-grid-child form-grid-child-threequarters">
                    <span class="label">Danh sách</span>
                    <span class="value js-list-value">{{ nameList }}</span>
                    <label>Danh sách</label>
                    <select class="select-list" :value="nameList" @change="setNameList">
                      <option v-for="item in list" :value="item.id" :key="item.id">{{ item.title }}</option>
                    </select>
                  </div>
                  <div class="button-link setting form-grid-child">
                    <span class="label">Vị trí</span>
                    <span class="value js-pos-value">{{ Number(indexCard) + 1 }}</span>
                    <label>Vị trí</label>
                    <select class="select-position" :value="indexCard" @change="setIndexCard">
                      <option v-for="item in directory.cards" :key="item.id" :value="item.index">
                        {{ item.index + +1 }}
                      </option>
                    </select></div>
                </div>
                <input class="nch-button nch-button--primary wide js-submit" @click="move" type="submit"
                       value="Di chuyển"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "@/api";

export default {
  name: "Move",
  props: ['offset', 'list'],
  data() {
    return {
      nameList: "",
      indexCard: this.offset.card.index,
      idList: "",
      directory: {}
    }
  },
  methods: {
    setNameList(e) {
      this.idList = e.target.value
      this.directory = this.list.find(value => value.id == this.idList);
      this.nameList = this.directory.title
      this.indexCard = 0
    },
    setIndexCard(e) {
      this.indexCard = e.target.value
    },
    closeMove() {
      this.$emit('closeMove')
    },
    move() {
      let payload = {
        index: this.indexCard,
        directory_id: this.directory.id
      }
      api.changeCardList(payload, this.offset.card.id).then(() => {
        this.$emit('updateCardList');
        this.closeMove()
      })
    }

  },

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
        text-align: left;

        h4 {
          color: #5e6c84;
          font-size: 12px;
          font-weight: 500;
          letter-spacing: .04em;
          line-height: 16px;
          margin-top: 16px;
          text-transform: uppercase;
        }

        .form-grid {
          display: flex;
          flex-wrap: wrap;
          width: 100%;
          margin-bottom: 10px;

          .setting {
            height: 48px;
            float: left;
            position: relative;
            margin-top: 0;
            flex: 3;
            margin-right: 8px;
            background-color: rgba(9, 30, 66, .04);
            box-shadow: none;
            border: none;
            border-radius: 3px;
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            max-width: 300px;
            overflow: hidden;
            padding: 6px 12px;
            text-decoration: none;
            text-overflow: ellipsis;
            -webkit-user-select: none;
            user-select: none;
            white-space: nowrap;
            transition-property: background-color, border-color, box-shadow;
            transition-duration: 85ms;
            transition-timing-function: ease;

            .label {
              color: #5e6c84;
              display: block;
              font-size: 12px;
              line-height: 16px;
              margin-bottom: 0;
            }

            .value {
              display: block;
              font-size: 14px;
              line-height: 20px;
              overflow: hidden;
              text-overflow: ellipsis;
            }

            .select-list {
              border: none;
              cursor: pointer;
              height: 50px;
              left: 0;
              margin: 0;
              opacity: 0;
              position: absolute;
              top: 0;
              z-index: 2;
              width: 100%;
            }

            .select-position {
              border: none;
              cursor: pointer;
              height: 50px;
              left: 0;
              margin: 0;
              opacity: 0;
              position: absolute;
              top: 0;
              z-index: 2;
              width: 100%;
            }
          }


        }

        input {
          background-color: #49852e;
          box-shadow: none;
          border: none;
          color: #fff;
          outline: 0;
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