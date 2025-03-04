<template>
    <div>
        <div v-if="formState == 'showForm'">
            <div class="important text-center">
                <p><strong>We're looking forward meeting you at the hack!</strong></p>

                <p>Prior to attendance, we need a bit of information from each attendee. If you have booked multiple tickets please enter the details for each person.<br><strong>This is your confirmation of attendance.</strong> Without this information we may not be able to register you.</p>
            </div>
            <hr>
            <div class="columns">
                <div class="column is-4 is-offset-4">
                    <div>
                        <ValidationObserver ref="observer" v-slot="{ invalid }"  @submit.prevent="submit()">

                            <div :class="fieldClass(field.type)" v-for="field in registerForm" :key="field.name">
                                <ValidationProvider :rules="field.rules" :name="field.name" v-slot="{ errors }">
                                    <label class="label" v-if="field.type !== 'checkbox'">{{field.label}} <small v-if="field.subLabel">{{field.subLabel}}</small></label>
                                    <input  v-if="field.type === 'input'" class="form-control" type="text" :placeholder="field.placeholder" v-model="field.value" >
                                    <textarea v-if="field.type === 'textarea'" class="form-control" :placeholder="field.placeholder" v-model="field.value"></textarea>
                                    <label>
                                        <input type="checkbox" v-model="field.value"> {{field.name}}
                                    </label>
                                    <select v-model="field.value" class="form-control" v-if="field.type === 'select'" >
                                        <option v-for="option in field.options" :key="option">{{option}}</option>
                                    </select>
                                    <p class="help-block text-warning">{{ removeHyphens(errors[0]) }}</p>
                                    <p v-if="field.info" class="info">{{ field.info }}</p>
                                </ValidationProvider>
                            </div>

                            <div class="checkbox">
                                <ValidationProvider rules="required" name="accept-terms-and-conditions" v-slot="{ errors }">
                                        <label>
                                            <input type="checkbox">
                                            I agree to the <a href="https://hackcodeofconduct.org/" target="_blank">Code of Conduct</a>
                                        </label>
                                        <p class="help-block text-warning">{{  removeHyphens(errors[0]) }}</p>
                                </ValidationProvider>
                            </div>

                        </ValidationObserver>

                        <fieldset>
                            <p>The information given in this form is held in strictest confidentiality and will be destroyed within 30 days after LincolnHack.<br>
                                Information is shared on a need to know basis (eg - the Chef will need to know of allergies)<br>
                                If you have any questions, please <a href="mailto:hello@lincolnhack.org">contact us.</a>
                            </p>
                        </fieldset>
                        <p>
                            <button class="btn btn-primary" @click="submit()">Submit</button>
                            <button class="btn btn-default" @click="cancel()">Cancel</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="formState = 'showThanks'">
            <p class="text-center"><strong>Thanks - We look forward to meeting you on 16th November!</strong></p>
        </div>

		<div v-if="formState = 'showError'">
            <p class="text-center"><strong>This email has already been registered - - We look forward to meeting you on 16th November!</strong></p>
		</div>

    </div>
</template>

<script>
	import axios from 'axios'
	export default {
		name: "attendance",
		data () {
			return {
				formState: 'showForm',
				submissionURL: null,
				registerForm: [
					{
						label: 'Name',
						name: 'name',
						type: 'input',
						value: null,
						rules: 'required'
					},
					{
						label: 'email',
						name: 'email',
						type: 'input',
						value: null,
						rules: 'required|email',
						info: 'We will use this email to sign you up to Digital Lincoln Slack so you can register your team with Hackbot in the LincolnHack channel. If you have any problems please contact us. If you\'re not signed up on the day we will help you.'
					},
					{
						label: 'Emergency Contact Name',
						name: 'emergency-name',
						type: 'input',
						value: null,
						rules: 'required'
					},
					{
						label: 'Emergency Contact Telephone Number',
						name: 'emergency-contact-number',
						type: 'input',
						value: null,
						rules: 'required',
						info: 'Used in case of emergency'
					},
					{
						label: 'Pies',
						subLabel: 'with optional mash, veg and gravy',
						name: 'pies',
						type: 'select',
						options: [
							'Lincolnshire Ale',
							'Beef & Vegetable',
							'Steak, Kidney & Mushroom',
							'Game and Madeira',
							'Lamb and Rosemary',
							'Chicken Leek and Mushroom',
							'Mediterranean Vegetable with Goats Cheese (V)',
							'Mediterranean Vegetable without Goats Cheese (V)',
							'BBQ Pulled Pork & Bourbon',
							'Vegan - we will be in touch',
							'Celiac / Gluten - we will be in touch'
						],
						value: null,
						rules: 'required'
					},
					{
						label: 'T Shirt Size',
						name: 't-shirt',
						type: 'select',
						options: [
							'S',
							'M',
							'L',
							'XL',
							'XXL'
						],
						value: null,
						rules: 'required'
					},
					{
						label: 'Allergies / Intolerances',
						name: 'allergies',
						type: 'textarea',
						value: null
					},
					{
						label: 'Anything we should know about?',
						subLabel: 'eg - medical',
						name: 'other',
						type: 'textarea',
						value: null
					}
				]
			}
		},
		methods: {
			fieldClass (fieldtype) {
				if (fieldtype === 'checkbox') {
					return 'checkbox'
                }
				return 'form-group'
            },
			removeHyphens (value) {
				if (!value) {
					return
				}
				return value.replace(/-/g, ' ')
			},
			cancel () {
				for (let item of this.registerForm) {
					item.value = null
				}
			},
			async submit () {
				const isValid = await this.$refs.observer.validate()
				if (isValid) {
					const formData = {}
					for (let item of this.registerForm) {
						formData[item.name] = item.value
					}
					axios.post(this.submissionURL, formData)
						.then((response) => {
							this.formState = 'showThanks'
						})
						.catch((error) => {
							this.formState = 'showError'
						})
				} else {
					alert('Oops! Missing some information - please check form')
				}
			}
		}
	}
</script>

<style scoped>
    label {
        text-transform: uppercase;
    }
    label small {
        color: #2c3e50;
        font-size: 0.85rem;
        text-transform: lowercase;
    }
    .info {
        color: #2c3e50;
        font-size: 0.75rem;
        font-style: italic;
    }
    fieldset {
        margin: 1rem 0;
        border: 1px dotted #cccccc;
        font-size: 0.85rem;
        font-weight: bold;
        padding: 1rem;
    }
    .important p {
        margin-bottom: 1rem;
    }
</style>
