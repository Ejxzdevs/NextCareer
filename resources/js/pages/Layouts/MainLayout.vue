<template>
    <div>
      <Navigational/>
      <div>
        <slot></slot>
      </div>
    </div>
</template>
<script>
  import Navigational from '../../components/shared/Navigational.vue';
 
  export default {
    components: {
      Navigational
    }
  };
</script>

  