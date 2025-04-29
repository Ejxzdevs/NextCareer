<template>
    <div>
      <Navigational/>
      <div>
        <slot></slot>
      </div>
    </div>
</template>
<script>
  import Navigational from '../../components/Navigational.vue';
 
  export default {
    components: {
      Navigational
    }
  };
</script>

  