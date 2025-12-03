<script setup>
import { animate, useMotionValue, useTransform, RowValue } from "motion-v"
import { onMounted, onUnmounted } from "vue"

const props = defineProps(['targetNumber']);

const count = useMotionValue(0)
const rounded = useTransform(() => Math.round(count.get()))

let controls

onMounted(() => {
    controls = animate(count, props.targetNumber, { duration: 3 })
})

onUnmounted(() => {
    controls?.stop()
})
</script>

<template>
    <RowValue :value="rounded" />
</template>