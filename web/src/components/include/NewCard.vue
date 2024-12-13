<template>
  <div class="cardComposer">
    <div class="cardTodo">
      <div class="detailCard">
        <div class="cardLabel"></div>
        <textarea name="" class="createCard" id="createCard" placeholder="Nhập tiêu đề cho thẻ này" cols="30"
                  rows="10" ref="cardTitle" @keydown.enter="AddCard"></textarea>
      </div>
    </div>
    <div class="cardControl">
      <div class="card-control-section">
        <el-button class="btnAddCard" type="success" @click="AddCard">Thêm thẻ</el-button>
        <div class="btnCloseAddCard" @click="closeAddCard"><i class="el-icon-close"></i></div>
      </div>
      <div class="card-control-section mod-right">
        <div class="menu"><i class="el-icon-more"></i></div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapState, mapMutations, mapGetters} from "vuex";

export default {
  name: "NewCard.vue",
  props:['directory'],
  methods: {
    ...mapMutations('home', [
      'addCard'
    ]),
    closeAddCard() {
      this.$emit('closeAddCard');
    },
    AddCard(){
      let title= this.$refs.cardTitle.value

      if (title.length > 0){
        let index = this.getList(this.directory.id).cards.length
        let data = {
          title: title,
          directory_id: this.directory.id,
          index: index,
        }

        this.$emit('addCard',data)
        this.closeAddCard()
      }

    }
  },
  mounted() {
    document.getElementById('createCard').focus()
  },
  computed: {
    ...mapState('home', [
      'lists'
    ]),
    ...mapGetters('home', [
      'getList'
    ])
  }
}
</script>

<style scoped lang="scss">
.cardComposer {
  padding-bottom: 8px;

  .cardTodo {
    background-color: #fff;
    border-radius: 3px;
    box-shadow: 0 1px 0 rgb(9 30 66 / 25%);
    cursor: pointer;
    //display: block;
    margin-bottom: 8px;
    max-width: 300px;
    min-height: 20px;
    position: relative;
    text-decoration: none;
    z-index: 0;

    .detailCard {
      overflow: hidden;
      padding: 6px 8px 2px;
      position: relative;
      z-index: 10;

      .createCard {
        box-sizing: border-box;
        overflow: hidden;
        overflow-wrap: break-word;
        resize: none;
        height: 54px;
        background: none;
        border: none;
        box-shadow: none;
        margin-bottom: 4px;
        max-height: 162px;
        min-height: 54px;
        overflow-y: auto;
        padding: 0;
        width: 100%;
      }

      textarea:focus {
        outline: none;
        border: none;
      }
    }
  }

  .cardControl {
    display: flex;
    justify-content: space-between;
    align-items: center;

    .card-control-section {
      display: flex;
      align-items: center;

      .btnAddCard {
        padding: 8px 12px;
      }

      .btnCloseAddCard {
        margin-left: 5px;
        color: #6b778c;
        font-size: 28px;
        font-weight: 600;
        cursor: pointer;
      }

      .btnCloseAddCard:hover {
        color: #172b4d;
      }
    }

    .mod-right {
      .menu {
        margin: 6px 4px 4px 4px;
        padding: 6px;
        text-align: center;
        cursor: pointer;
        line-height: 20px;
        height: 28px;
        width: 28px;
        z-index: 99;
        //font-size: 16px;
      }

      .menu:hover {
        background-color: rgba(9, 30, 66, .08);
        //border-radius: 2px;
      }
    }
  }
}
</style>