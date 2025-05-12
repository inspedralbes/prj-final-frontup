<template>
    <div v-if="visible" class="alert-overlay">
      <div class="alert" :class="{ 'alert-success': success, 'alert-error': !success }">
        <div class="alert-icon-container">
          <div class="alert-icon" :class="{ 'success': success, 'error': !success }">
            <svg v-if="success" class="icon-success" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
              <path d="M20 6L9 17l-5-5" />
            </svg>
            <svg v-else class="icon-error" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
            <svg class="circle-animation" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36">
              <circle class="circle-bg" cx="18" cy="18" r="16" fill="none" />
              <circle class="circle" cx="18" cy="18" r="16" fill="none" />
            </svg>
          </div>
        </div>
        <div class="alert-content">{{ text }}</div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'AlertComponent',
    props: {
      success: {
        type: Boolean,
        default: true
      },
      text: {
        type: String,
        required: true
      },
      duration: {
        type: Number,
        default: 3000
      }
    },
    data() {
      return {
        visible: false,
        timeout: null
      }
    },
    mounted() {
      this.show()
    },
    beforeUnmount() {
      if (this.timeout) {
        clearTimeout(this.timeout)
      }
    },
    methods: {
      show() {
        this.visible = true
        this.timeout = setTimeout(() => {
          this.close()
        }, this.duration)
      },
      close() {
        this.visible = false
        this.$emit('close')
      }
    }
  }
  </script>
  
  <style scoped>
  .alert-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
    animation: fadeIn 0.3s ease-out;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  .alert {
    background-color: #242832;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    width: 300px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
    animation: scaleIn 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
    transform-origin: center;
  }
  
  @keyframes scaleIn {
    0% { transform: scale(0.8); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
  }
  
  .alert-success {
    border: 2px solid #34c759;
  }
  
  .alert-error {
    border: 2px solid #ff3b30;
  }
  
  .alert-icon-container {
    display: flex;
    justify-content: center;
    margin-bottom: 25px;
  }
  
  .alert-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
  }
  
  .alert-icon.success {
    background-color: rgba(52, 199, 89, 0.1);
  }
  
  .alert-icon.error {
    background-color: rgba(255, 59, 48, 0.1);
  }
  
  .icon-success, .icon-error {
    width: 40px;
    height: 40px;
    position: relative;
    z-index: 2;
    opacity: 0;
    animation: fadeInIcon 0.3s ease-out 0.8s forwards;
  }
  
  @keyframes fadeInIcon {
    from { opacity: 0; transform: scale(0.5); }
    to { opacity: 1; transform: scale(1); }
  }
  
  .icon-success {
    color: #34c759;
  }
  
  .icon-error {
    color: #ff3b30;
  }
  
  .circle-animation {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
  }
  
  .circle-bg {
    stroke: rgba(255, 255, 255, 0.1);
    stroke-width: 2;
  }
  
  .circle {
    stroke-width: 3;
    stroke-linecap: round;
    stroke-dasharray: 100;
    stroke-dashoffset: 100;
    transform: rotate(-90deg);
    transform-origin: center;
    animation: circleAnimation 0.8s ease-out forwards;
  }
  
  .alert-success .circle {
    stroke: #34c759;
  }
  
  .alert-error .circle {
    stroke: #ff3b30;
  }
  
  @keyframes circleAnimation {
    from { stroke-dashoffset: 100; }
    to { stroke-dashoffset: 0; }
  }
  
  .alert-content {
    color: #ffffff;
    font-size: 18px;
    font-weight: 500;
    animation: fadeInText 0.5s ease-out 0.5s both;
  }
  
  @keyframes fadeInText {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  </style>