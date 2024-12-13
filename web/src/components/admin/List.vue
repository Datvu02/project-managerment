<template>
  <div class="listWrapper">
    <div class="listContent">
      <div class="listHeader">
        <textarea class="list-header-name list-header-edit-name"
                  @keydown.enter="updateListTitle" @blur="updateListTitle" name="" id=""
                  cols="30" rows="10" :value="listTitle" placeholder="Nhập vào tiêu đề danh sách..."
        ></textarea>
        <div class="menu" @click="openActionList"><i class="el-icon-more"></i></div>
      </div>
      <div class="listCard">
        <draggable
            class="dragArea list-group"
            :list="item.cards"
            item-key="id"
            :animation="100"
            group="todo"
            @end="moveTodo"
        >
          <Todo v-for="(card,index) in item.cards" @openQuickEdit="openQuickEdit" :key="index"
                @updateData="updateCardList"
                @openDetailCard="openDetailCard"
                :id="card.id" :card="card"/>
        </draggable>
        <NewCard v-if="cardAddOpen" v-click-outside="closeAddCard" @addCard="handleAddCard" @closeAddCard="closeAddCard"
                 :directory="item"></NewCard>
      </div>
      <div class="listFooter" v-if="!cardAddOpen">
        <div class="openCard" @click="openAddCard">
          <span class="icon-add-card"><i class="el-icon-plus"></i></span>
          <span class="text-add-card">Thêm thẻ khác</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Todo from "@/components/admin/Todo";
import draggable from 'vuedraggable'
import ClickOutside from 'vue-click-outside'
import NewCard from "@/components/include/NewCard";
import api from "@/api";

export default {
  name: "List",
  props: ['item', 'index'],
  data() {
    return {
      'listTitle': '',
      'cardAddOpen': false,
    }
  },
  components: {
    Todo,
    draggable,
    NewCard
  },
  methods: {
    moveTodo(e) {
      let id = e.clone.id
      let todo = e.to.parentElement
      let directory = todo.parentElement;
      let toId = directory.parentElement.getAttribute('id')

      let payload = {
        index: e.newIndex,
        directory_id: toId
      }
      api.changeCardList(payload, id).then(() => {
        this.$emit('updateCardList');
      })

    },
    openActionList(e) {
      let rect = e.target.getBoundingClientRect();
      let data = {
        left: rect.left,
        top: rect.top,
        id: this.item.id
      };
      this.$emit('openActionList', data)

    },
    loadTitle() {
      this.listTitle = this.item.title;
    },
    openAddCard() {
      console.log('a')
      this.cardAddOpen = true;
    },

    closeAddCard() {
      this.cardAddOpen = false;
    },
    updateListTitle(e) {
      e.target.blur();
      let updateTile = {
        data: {
          title: e.target.value
        },
        id: this.item.id
      }
      this.$emit('updateListTitle', updateTile);
    },
    handleAddCard(data) {
      api.addCards(data).then(() => {
        this.updateCardList();
      })
    },
    updateCardList() {
      this.$emit('updateCardList');
    },
    handleShowControl(data) {
      this.$emit('handleShowControl', data)
    },
    closeControlModal() {
      this.$emit('closeControlModal')
    },
    openQuickEdit(data) {
      this.$emit('openQuickEdit', data)
    },
    openDetailCard(id) {
      this.$emit('openDetailCard', id)
    }
  },
  mounted() {
    this.loadTitle();
    this.popupItem = this.$el
  },
  updated() {
    this.loadTitle();
  },

  // do not forget this section
  directives: {
    ClickOutside,
  }
}
</script>

<style lang="scss" scoped>
.listWrapper {
  width: 272px;
  margin: 0 4px;
  height: 100%;
  box-sizing: border-box;
  display: inline-block;
  vertical-align: top;
  white-space: nowrap;

  .listContent {
    background-color: #ebecf0;
    border-radius: 3px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    max-height: 100%;
    position: relative;
    white-space: normal;


    .listHeader {
      flex: 0 0 auto;
      display: flex;
      padding-left: 10px;
      position: relative;
      min-height: 20px;

      .list-header-target {
        cursor: pointer;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
      }

      .menu {
        margin: 6px 4px 4px 4px;
        padding: 6px;
        text-align: center;
        cursor: pointer;
        line-height: 20px;
        height: 22px;
        width: 22px;
        z-index: 99;
        font-size: 16px;
      }

      .menu:hover {
        background-color: rgba(9, 30, 66, .08);
        //border-radius: 2px;
      }

      .list-header-name {
        height: 22px;
        margin: 0;
      }

      .list-header-edit-name {
        resize: none;
        font-size: 16px;
        line-height: 20px;
        font-weight: 600;
        background-color: #ebecf0;
        border: none;
        height: 22px;
        width: 98%;
        margin: 6px 0;
        padding: 4px 0 4px 8px;
        cursor: pointer;
        overflow: hidden;
      }

      textarea:focus {
        outline: none;
        background-color: white !important;
        box-shadow: inset 0 0 0 2px #0079bf;
        border-color: #0079bf;
        overflow: hidden;
        overflow-wrap: break-word;
        border-radius: 2px;
        user-select: all;
      }

      textarea::-webkit-input-placeholder {
        font-weight: 200;
        font-size: 14px;
      }
    }

    .listFooter {
      min-height: 38px;
      max-height: 38px;
      display: flex;
      justify-content: space-between;
      margin-top: 5px;
      margin-bottom: 8px;

      .openCard {
        border-radius: 3px;
        color: #5e6c84;
        display: block;
        flex: 1 0 auto;
        margin: 2px 10px 2px 10px;
        padding: 8px 8px;
        position: relative;
        text-decoration: none;
        -webkit-user-select: none;
        user-select: none;
        text-align: left;
        cursor: pointer;

        .icon-add-card {
          margin-right: 2px;
        }

        .text-add-card {
          font-size: 14px;
        }
      }

      .openCard:hover {
        background-color: rgba(9, 30, 66, .08);
        color: #172b4d;
      }
    }

    .listCard {
      flex: 1 1 auto;
      margin-bottom: 0;
      overflow-y: auto;
      overflow-x: hidden;
      margin: 0 4px;
      padding: 0 4px;
      z-index: 1;
      min-height: 0;


    }

    /* width */
    .listCard::-webkit-scrollbar {
      width: 8px;
    }

    /* Track */
    .listCard::-webkit-scrollbar-track {
      border-radius: 7px;
      background-color: #4f4f5073;
    }

    /* Handle */
    .listCard::-webkit-scrollbar-thumb {
      background: #bcbcbe63;
      border-radius: 7px;
    }

    /* Handle on hover */
    .listCard::-webkit-scrollbar-thumb:hover {
      background: #d3d3d87d;
    }
  }

}

.listWrapper:first-child {
  margin-left: 8px;
}
</style>