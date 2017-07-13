<template>
    <div class="uk-form uk-form-horizontal">
        <h1>{{ 'Detect AdBlocker Settings' | trans }}</h1>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Pages' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <input-tree :active.sync="package.config.nodes"></input-tree>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Modal Content' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <v-editor type="code" :value.sync="package.config.html"></v-editor>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Remove Added Events after Rendering' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <input type="checkbox" v-model="package.config.reset">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Check Interval' | trans }}</label>
            <div class="uk-form-controls">
                <input class="uk-form-width-small uk-text-right" type="number"
                       v-model="package.config.checktime" min="0" number>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Number of Loops' | trans }}</label>
            <div class="uk-form-controls">
                <input class="uk-form-width-small uk-text-right" type="number"
                       v-model="package.config.loopnumber" min="0" number>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Bait Class' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <p class="uk-form-controls-condensed">
                    <input type="text" class="uk-form-width-large" v-model="package.config.baitclass">
                </p>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Bait Style' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <p class="uk-form-controls-condensed">
                    <input type="text" class="uk-form-width-large" v-model="package.config.baitstyle">
                </p>
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Enable Debug Mode' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <input type="checkbox" v-model="package.config.debug">
            </div>
        </div>
        <div class="uk-form-row">
            <label class="uk-form-label">{{ 'Sticky Modal' | trans }}</label>
            <div class="uk-form-controls uk-form-controls-text">
                <input type="checkbox" v-model="package.config.sticky">
            </div>
        </div>
        <div class="uk-form-row uk-margin-top">
            <div class="uk-form-controls">
                <button class="uk-button uk-button-primary" @click="save">{{ 'Save' | trans }}</button>
            </div>
        </div>
    </div>
</template>

<script>

module.exports = {

	settings: true,

	props: ['package'],

	methods: {
		save: function save() {
			this.$http.post ('admin/system/settings/config', {
				name: 'spqr/detectadblocker',
				config: this.package.config
			}).then (function () {
				this.$notify ('Settings saved.', '');
			}).catch (function (response) {
				this.$notify (response.message, 'danger');
			}).finally (function () {
				this.$parent.close ();
			});
		}
	}
};

window.Extensions.components['detectadblocker-settings'] = module.exports;
</script>