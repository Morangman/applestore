<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.category.form.name') }}</strong>
                        </label>
                        <input
                            name="name"
                            type="text"
                            v-model="model.name"
                            class="form-control"
                            :class="{ 'border-danger': errors.name }"
                        >
                        <div v-for="(error, i) in errors.name"
                             :key="`name__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div v-for="(product, i) in model.products" :key="`product_${i}`" class="row" v-if="model.id">
                        <div class="product-title col-md-2">
                            <div class="form-group">
                                <a :href="$r('admin.product.edit', { product: product.id })">{{ product.title }}</a>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>
                                    <strong>{{ $t('admin.category.form.price') }}</strong>
                                </label>
                                <input
                                    name="price"
                                    v-model="product.price"
                                    type="number"
                                    class="form-control"
                                >
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>
                                    <strong>{{ $t('admin.category.form.old_price') }}</strong>
                                </label>
                                <input
                                    name="old_price"
                                    v-model="product.old_price"
                                    type="number"
                                    class="form-control"
                                >
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.product.form.status') }}</strong>
                        </label>
                        <b-form-checkbox
                            v-model="model.is_hidden"
                            value="1"
                            unchecked-value="0"
                        >
                            {{ $t('admin.product.form.hidden') }}
                        </b-form-checkbox>
                        <div v-for="(error, i) in errors.is_hidden"
                             :key="`is_hidden__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <template v-if="model.id">
                    <button
                        type="submit"
                        class="btn btn-danger"
                        @click.prevent="deleteCategory"
                    >
                        {{ $t('common.word.delete') }}
                    </button>
                </template>
                <button
                    type="submit"
                    class="btn btn-primary"
                    @click.prevent="submit"
                >
                    {{ $t('common.word.save') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import FormHelper from '../../mixins/form_helper';

    export default {
        props: {
            model: {
                type: Object,
                required: true,
            },
            products: {
                type: Array,
                required: false,
            },
            errors: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        methods: {
            submit() {
                this.$emit('submit', this.model);
            },

            deleteCategory() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },
        },

        created() {
            if (this.model.id) {
                this.model.is_hidden = Number(this.model.is_hidden);
            }
        },
    };
</script>
