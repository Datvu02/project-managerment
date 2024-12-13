<template>
  <span class="card-label" @click="activeLabel(color)" :class="['card-label-'+color]">{{ title }}
    <span class="icon-sm icon-check card-label-selectable-icon" v-if="showIcon">
      <i class="el-icon-check"></i>
    </span>
  </span>
</template>

<script>
import _ from 'lodash'

export default {
  name: "DetailLabel",
  props: ['color', 'title', 'card', 'id', 'isActive'],
  data() {
    return {
      showIcon: false,
      dataActive: ''
    }
  },
  methods: {
    activeLabel(color) {
      let data = {
        label_id: this.id,
        color: color,
        isActive: this.showIcon
      }
      this.$emit('activeLabel', data);
      this.showIcon = !this.showIcon
    },
    checkActive() {
      if (!_.isEmpty(this.card)) {
        this.card.labels.map((label) => {
          if (label.id === this.id) {
            this.showIcon = true
          }
        })
      }
    },
    checkActiveEdit() {
      if (this.isActive.color === this.color) {
        this.showIcon = true;
        this.dataActive = this.color
      }
    }

  },
  mounted() {
    this.checkActive()
    this.checkActiveEdit()
  },
  watch: {
    card: function (val) {
        val.labels.map((label) => {
          if (label.id === this.id) {
            this.showIcon = true
          }
        })
    },
    isActive: function (val) {
      if (val.color === this.color) {
        this.showIcon = true;
        this.dataActive = this.color
      }
    }
  }
}
</script>

<style scoped lang="scss">
@import "src/assets/scss/card_color";

.card-label {
  border-radius: 3px;
  cursor: pointer;
  font-weight: 700;
  margin: 0 0 4px;
  min-height: 20px;
  padding: 6px 12px;
  position: relative;
  transition: padding 85ms, margin 85ms, box-shadow 85ms;
  width: 100%;
  color: #fff;
  display: block;
  max-width: 90%;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;

  span {
    height: 20px;
    font-size: 16px;
    line-height: 20px;
    width: 20px;
    -webkit-font-smoothing: antialiased;
    display: inline-block;
    font-family: trellicons;
    font-style: normal;
    font-weight: 400;
    text-align: center;
    vertical-align: bottom;
    left: 14px;
    position: absolute;
    top: 6px;
  }
}

.card-label:hover {
  opacity: 0.9;
}
</style>