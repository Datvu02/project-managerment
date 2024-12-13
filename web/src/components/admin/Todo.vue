<template>
  <div class="cardTodo" @contextmenu="handler()">
    <div class="detailTodo" @click.left="openDetailCard" @click.right="openQuickEdit">
      <span class="btn-edit" @click="btnOpenEdit">
        <i class="el-icon-edit"></i>
      </span>
      <div class="list-card-labels">
        <span class="card-label card-label-default mod-card-front" @click="handleShowLable"
              :class="[{'labelShow' :labelShow}, 'card-label-'+label.color]" v-for="label in card.labels"
              :key="label.id" title="LMS Thầy thiện">
          <span class="label-text" v-if="labelShow">{{ label.name }}</span>
        </span>
      </div>
      <p class="text-title">{{ card.title }}</p>
      <div class="badges">
        <span class="js-badges">
          <div class="badge js-due-date-badge mod-due-date"
               :class="[{'is-due-complete':card.status === 3},{'is-due-die': card.status===1},{'is-due-near': card.status===2}]"
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
        <img v-if="card.user && card.user.avatar && card.user.avatar.length > 0"
             style="width: 30px; height: 30px; position: absolute; bottom: 5px; right: 5px; border-radius: 50%"
             :src="'http://127.0.0.1:8000/storage/users/'+ card.user.avatar" alt="">
        <img v-if="card.user && card.user.avatar.length === 0"
             style="width: 30px; height: 30px; position: absolute; bottom: 5px; right: 5px; border-radius: 50%"
             src="https://image.shutterstock.com/image-vector/default-avatar-profile-icon-grey-260nw-518740753.jpg" alt="">
      </div>
    </div>
  </div>
</template>

<script>
import {mapMutations, mapState} from "vuex";
import api from "@/api";
import moment from "moment";

export default {
  name: "Todo",
  props: ['card'],
  components: {},
  data() {
    return {
      modalShow: false,
      dialogFormVisible: false,
      editDescriptionModal: false,
      showControlModalSidebar: false,
      offsetLabel: {},
      labels: [],
      isComplete: false,
      isDeadline: 0,
      cardTitle: '',
      deadline: '',
      description: ''
    }
  },
  methods: {
    ...mapMutations('home', [
      'showLable', 'updateCardDetail'
    ]),
    handler(e){
      e.preventDefault()
    },
    handleShowLable(e) {
      e.stopPropagation()
      this.showLable()
    },
    changeStatusTodo() {
      let data = {};

      if (this.isComplete) {
        data.status = 3;
      } else {
        data.status = 0
      }

      data.directory_id = this.card.directory_id
      api.changeStatusTodo(data, this.card.id).then(() => {
        this.getDetailCard();
        this.$emit('updateData')
      })
    },
    changeDeadline(data) {
      api.changeStatusDeadline(data, this.card.id).then(() => {
        this.getDetailCard();
        this.$emit('updateData')
      })

      this.resetTime()
    },
    resetTime() {
      this.isDeadline = 0;
      this.isComplete = false;
      this.deadline = ''
    },
    getDetailCard() {
      api.getCard(this.card.id).then((response) => {
        this.updateCardDetail(response.data.data);
      })
    },
    handleShowControl(data) {
      // this.showControlModalSidebar = false;

      this.$emit('handleShowControl', data)
    },
    openControlLabel(e) {
      let rect = e.target.getBoundingClientRect();
      let data = {
        left: rect.left,
        top: rect.top,
        type: 'label',
        id: this.card.id
      };
      this.$emit('handleShowControl', data)
    },
    openDetailCard() {
      this.$emit('openDetailCard', this.card.id)
    },
    closeControlModal() {
      this.$emit('closeControlModal')
    },
    btnOpenEdit(e) {
      e.stopPropagation()
      let parent = e.target.parentElement.parentElement
      let rect = parent.getBoundingClientRect();
      let data = {
        left: rect.left,
        top: rect.top,
        width: '256px',
        id: this.card.id
      }
      this.$emit('openQuickEdit', data)
    },
    openQuickEdit(e) {
      let rect = e.target.getBoundingClientRect();
      let data = {
        left: rect.left,
        top: rect.top,
        width: '256px',
        id: this.card.id
      }
      this.$emit('openQuickEdit', data)
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
    }
  },
  computed: {
    ...mapState('home', [
      'labelShow', 'cardDetail'
    ]),
  },
  mounted() {

    this.checkComplate()
    this.checkDeadline()
  },
  watch: {
    card: function () {
      this.checkComplate()
      this.checkDeadline()
    }
  },
}
</script>

<style lang="scss" scoped>
@import "../../assets/scss/card_color";

.cardTodo {
  width: 100%;
  max-height: 162px;
  overflow: hidden;
  border-radius: 3px;
  box-shadow: 0 1px 0 rgb(9 30 66 / 25%);
  border: none;
  background-color: #fff;
  cursor: pointer;
  display: block;
  margin-bottom: 8px;
  max-width: 300px;
  min-height: 20px;
  position: relative;
  text-decoration: none;
  z-index: 0;

  .detailTodo {
    overflow: hidden;
    padding: 12px 8px 0 8px;
    position: relative;
    z-index: 10;

    .list-card-labels {
      overflow: auto;
      position: relative;

      .card-label {
        height: 8px;
        line-height: 16px;
        padding: 0 8px;
        max-width: 198px;
        text-shadow: none;
        width: auto;
        min-width: 40px;
        margin: 0 4px 4px 0;
        float: left;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        border-radius: 5px;
      }

      .labelShow {
        height: 16px !important;
      }
    }

    .btn-edit {
      background-color: #f4f5f7;
      background-clip: padding-box;
      background-origin: padding-box;
      border-radius: 3px;
      opacity: .8;
      padding: 4px;
      position: absolute;
      right: 2px;
      top: 2px;
      visibility: hidden;
      z-index: 40;

      i {
        color: #42526e;
        height: 20px;
        font-size: 16px;
        line-height: 20px;
        width: 20px;
      }
    }

    .btn-edit:hover {
      background-color: #EBECF0;
    }

    .text-title {
      margin: 0;
      text-indent: 40px;
      direction: rtl;
      text-align: left;
    }

    .badges {
      float: left;
      max-width: 100%;
      margin-left: -2px;
      margin-top: 10px;

      .badge {
        border-radius: 5px;
        color: #5e6c84;
        display: inline-block;
        margin: 0 4px 4px 0;
        max-width: 100%;
        min-height: 20px;
        overflow: hidden;
        position: relative;
        padding: 2px;
        text-decoration: none;
        text-overflow: ellipsis;
        vertical-align: top;
        cursor: pointer;

        .badge-icon {
          color: #6b778c;
          vertical-align: top;
          margin: 0 2px;

          .el-checkbox {
            margin: 0;

            .is-checked {
              .el-checkbox__inner {
                background-color: #61BD4F !important;
              }
            }
          }
        }

        .icon-checkbox-checked {
          display: none;
        }

        .icon-clock {
          display: inline-block;
        }

        .badge-text {
          font-size: 12px;
          padding: 0 4px 0 2px;
          vertical-align: top;
          white-space: nowrap;
        }
      }

      .badge:hover {
        background-color: #ebecf0;

        .icon-checkbox-checked {
          display: inline-block;
        }

        .icon-clock {
          display: none;
        }
      }

      .badge.is-due-complete {
        background-color: #61bd4f !important;
        border-radius: 3px;
        color: #fff;

        .badge-icon {
          color: #fff;
          vertical-align: top;
        }
      }

      .badge.is-due-near {
        background-color: #f2d600;
        border-radius: 3px;
        color: #fff;

        .badge-icon {
          color: #fff;
          vertical-align: top;
        }
      }

      .badge.is-due-die {
        background-color: #eb5a46;
        border-radius: 3px;
        color: #fff;

        .badge-icon {
          color: #fff;
          vertical-align: top;
        }
      }
    }
  }

  .thisCard:hover {
    background-color: #F4F5F7;
  }
}

.cardTodo:hover .btn-edit {
  visibility: visible;
}


</style>