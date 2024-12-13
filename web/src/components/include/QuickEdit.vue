<template>
  <div class="quick-card-editor">
    <span @click="closeQuickEdit" class="icon-lg icon-close quick-card-editor-close-icon js-close-editor">
    <i class="el-icon-close"></i>
  </span>
    <div class="quick-card-editor-card"
         :style="{left: offset.left+'px', top: offset.top+'px', width: offset.width+'px'}">
      <div class="list-card list-card-quick-edit js-stop" style="width: 256px;">
        <div class="list-card-stickers-area js-stickers-area hide">
          <div class="stickers js-card-stickers"></div>
        </div>
        <div class="list-card-details js-card-details">
          <div class="list-card-labels">
            <span class="card-label card-label-default mod-card-front"
                  :class="[{'labelShow' :labelShow}, 'card-label-'+label.color]" v-for="(label,index) in card.labels"
                  :key="index" :title="label.title">
              <span class="label-text" v-if="labelShow">{{ label.name }}</span>
            </span>
          </div>
          <textarea-autosize
              :value="card.title"
              class="list-card-edit-title"
              placeholder="Thêm mô tả chi tiết hơn..."
              id="quickEditCard"
              :min-height="30"

              @keydown.enter="updateCard"
          />
          <div class="badges">
            <span class="js-badges">
            <div class="badge js-due-date-badge mod-due-date"
                 :class="[{'is-due-complete':isComplete},{'is-due-die': isDeadline===2},{'is-due-near': isDeadline===1}]"
                 title="This card is complete."
                 v-if="card.deadline!=null">
            <span class="badge-icon icon-sm icon-clock badge-due-icon"> <i
                class="el-icon-alarm-clock badge-due-icon"></i></span>
            <span class="badge-icon icon-sm icon-checkbox-checked badge-due-checked" @click="changeStatusTodo">
              <el-checkbox v-model="isComplete"
                           @change="changeStatusTodo"></el-checkbox>
            </span>
            <span class="badge-text js-due-date-text">{{ formatStringDate(card.deadline) }}</span>
          </div>
        </span>
            <span class="custom-field-front-badges js-custom-field-badges"><span></span></span>
            <span class="js-plugin-badges"><span></span></span>
          </div>
          <div class="list-card-members js-list-card-members"></div>
        </div>
      </div>
      <input class="nch-button nch-button--primary wide js-save-edits" @click="updateCard" type="submit" value="Lưu">
      <a href="#" class="btn-close-quick-edit"><i class="el-icon-close"></i></a>
      <div class="quick-card-editor-buttons fade-in">
        <a class="quick-card-editor-buttons-item" @click="openModal" href="#">
          <span class="icon-sm icon-card light"><i class="el-icon-bank-card"></i></span>
          <span class="quick-card-editor-buttons-item-text">Mở Thẻ</span>
        </a>
        <a class="quick-card-editor-buttons-item js-edit-labels" @click="showControl($event,'label')" href="#"><span
            class="icon-sm icon-label light"><i class="el-icon-price-tag"></i></span><span
            class="quick-card-editor-buttons-item-text">Chỉnh sửa nhãn</span>
        </a>
        <a class="quick-card-editor-buttons-item deadline-quick-edit"><span
            class="icon-sm icon-label light"><i class="el-icon-time"></i></span><span
            class="quick-card-editor-buttons-item-text">Chỉnh sửa thời gian</span>
          <el-date-picker
              type="datetime"
              v-model="deadLine"
              @change="changeDeadline"
          >
          </el-date-picker>
        </a>
        <a class="quick-card-editor-buttons-item js-edit-labels" @click="moveCard" href="#"><span
            class="icon-sm icon-label light"><i class="el-icon-right"></i></span><span
            class="quick-card-editor-buttons-item-text">Di chuyển</span>
        </a>
        <a class="quick-card-editor-buttons-item js-edit-labels" @click="deleteCard" href="#"><span
            class="icon-sm icon-label light"><i class="el-icon-delete"></i></span><span
            class="quick-card-editor-buttons-item-text">Xóa thẻ</span>
        </a>
        <a class="quick-card-editor-buttons-item js-edit-labels" @click="closeQuickEdit" href="#"><span
            class="icon-sm icon-label light"><i class="el-icon-close"></i></span><span
            class="quick-card-editor-buttons-item-text">Đóng</span>
        </a>
        <div id="convert-card-role-button-react-root" class="">
          <div class="js-react-root"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {mapMutations, mapState} from "vuex";
import ClickOutside from 'vue-click-outside'
import moment from "moment";
import api from "@/api";

export default {
  name: "QuickEdit",
  props: ['card', 'offset'],
  data() {
    return {

      isComplete: false,
      isDeadline: 0,
      deadLine: ''
    }
  },
  methods: {
    ...mapMutations('home', [
      'showLable'
    ]),
    deleteCard() {
      this.$emit('deleteCard', this.card.id)
      this.closeQuickEdit()
    },
    closeQuickEdit() {
      this.$emit('closeQuickEdit')
    },
    showControl(e, type) {
      let rect = e.target.getBoundingClientRect();
      let data = {
        left: rect.left,
        top: rect.top,
        type: type
      };
      this.$emit('showControl', data)
    },
    moveCard(e) {
      let rect = e.target.getBoundingClientRect();
      let data = {
        left: rect.left,
        top: rect.top,
        card: this.card
      };
      this.$emit('showMove', data)
    },
    updateCard() {
      let data = {
        id: this.card.id,
        title: document.getElementById('quickEditCard').value
      }

      this.$emit('updateCard', data)
      this.closeQuickEdit()
    },
    openModal() {
      this.$emit('openModal', this.card.id)
    },
    formatStringDate(dateString) {
      let date = moment(dateString)
      return date.date() + ' tháng ' + (date.month() + +1);
    }, formatDate(dateString) {
      return 'ngày ' + moment(dateString).format('DD-MM-yyyy  HH:mm:ss');
    },
    checkComplate() {
      if (this.card.status == 0) {
        this.isComplete = false
      } else {
        this.isComplete = true
      }
    },
    checkDeadline() {
      let deadline = moment(this.card.deadline);
      let now = moment();
      if (deadline < now) {
        this.isDeadline = 2;
      } else if (deadline.format('YYYY-MM-dd') === now.format('YYYY-MM-dd')) {
        this.isDeadline = 1;
      } else {
        this.isDeadline = 0;
      }
    },
    changeStatusTodo() {
      let data = {};

      if (this.isComplete) {
        data.status = 1;
      } else {
        data.status = 0
      }

      data.directory_id = this.card.directory_id
      api.changeStatusTodo(data, this.card.id).then(() => {
        this.getDetailCard();
        this.$emit('updateCardList')
      })
    },
    changeDeadline(data) {
      api.changeStatusDeadline(data, this.card.id).then(() => {
        this.getDetailCard();
        this.$emit('updateData')
      })

      this.resetTime()
    },
    getDetailCard() {
      api.getCard(this.card.id).then((response) => {
        this.updateCardDetail(response.data.data);
      })
    }
  },
  computed: {
    ...mapState('home', [
      'labelShow'
    ])
  },
  mounted() {
    this.popupItem = this.$el
    this.checkComplate()
    this.checkDeadline()
  },
  watch: {
    card: function () {
      this.checkComplate()
      this.checkDeadline()
    }
  }
  ,
  // do not forget this section
  directives: {
    ClickOutside
  }
}
</script>

<style scoped lang="scss">
@import "src/assets/scss/quick_edit";
</style>