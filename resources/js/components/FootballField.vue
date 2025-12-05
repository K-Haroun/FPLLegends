<script setup>
import VueSoccerField from 'vue-soccer-field';

const visitors = [
          {number: 1, name: 'Yashin'},
          {number: 2, name: 'Maldini'},
          {number: 3, name: 'Cafu'},
          {number: 4, name: 'Beckenbauer'},
          {number: 5, name: 'Xavi'},
          {number: 6, name: 'Matthaus'},
          {number: 7, name: 'Christiano'},
          {number: 8, name: 'Pelé'},
          {number: 9, name: 'Ronaldo'},
          {number: 10, name: 'Maradona'},
          {number: 11, name: 'Messi'},
        ]

const receivers = [
          {number: 1, name: 'Yashin'},
          {number: 2, name: 'Maldini'},
          {number: 3, name: 'Cafu'},
          {number: 4, name: 'Beckenbauer'},
          {number: 5, name: 'Xavi'},
          {number: 6, name: 'Matthaus'},
          {number: 7, name: 'Christiano'},
          {number: 8, name: 'Pelé'},
          {number: 9, name: 'Ronaldo'},
          {number: 10, name: 'Maradona'},
          {number: 11, name: 'Messi'},
        ]
</script>

<template>
    <VueSoccerField orientation="portrait" :visitors="visitors" :receivers="receivers"
        style="width: 360px; height: 600px;" />
</template>