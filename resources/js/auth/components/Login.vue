<template>
  <div class="text-center">
    <div class="form-signin">
      <img
        class="mb-4"
        src="/img/CNE.png"
        alt="Coordenação Nacional de Emergência - Cruz Vermelha Portuguesa"
        width="150"
        height="150"
      />
      <a v-bind:href="microsoftRoute">
        <img src="/img/signinms.png" />
      </a>
    </div>
  </div>
</template>

<script>
export default {
  props: ["microsoftRoute"],
};
</script>