<script>
import { Inertia } from '@inertiajs/inertia';
import Pagination from './Pagination.vue'

export default {
    components: {
        Pagination
    },
    props: {
        properties : Object,
        propertyTypes : Array,
        selectedNumBedrooms: Number,
        selectedPrice: Number,
        selectedDescription: String,
        selectedPropertyType: String,
        selectedAvailableType: String
    },
    methods: {
        clearForm() {
            this.$refs.filters.reset();
            this.selectedAvailableType = null;
        },
        destroy(uuid) {
            console.log(this.$inertia.delete(`/property/${uuid}`));
            
            // this.$inertia.get('/');
        }
    }
}
</script>
<template>
<div class="flex flex-col md:flex-row mx-10 space-x-14">
    <aside class="hidden lg:block lg:w-1/4">
        <div class="sticky top-16 max-h-screen">
            <form class="mx-auto p-4 border rounded-lg shadow-lg" ref='filters'>
            <div class="mb-8">
                <h1 class="text-2xl text-center">Filter Properties</h1>
            </div>
            <div class="mb-8">
                <label for="num_bedrooms" class="block">Number of Bedrooms</label>
                <input type="number" name="num_bedrooms" class="w-full px-3 py-2 border rounded-lg" :value="selectedNumBedrooms">
            </div>
            <div class="mb-8">
                <label for="price" class="block">Price</label>
                <input type="number" name="price" class="w-full px-3 py-2 border rounded-lg" :value="selectedPrice" placeholder="">
            </div>
            <div class="mb-8">
                <label for="description" class="block">Match description</label>
                <input type="text" name="description" class="w-full px-3 py-2 border rounded-lg" :value="selectedDescription">
            </div>
            <div class="mb-8">
                <label for="property_type" class="block">Choose Property Type</label>
                <select name="property_type" class="w-full px-3 py-2 border rounded-lg" for="propertyTypeOption">
                    <option value selected>Select</option>
                    <option
                        v-for="propertyType in propertyTypes"
                        :key="propertyType"
                        :value="propertyType"
                        :selected="propertyType === selectedPropertyType ? 'selected' : null"
                        >
                        {{ propertyType }}
                    </option>
                </select>
            </div>
            <div class="mb-8">
                <label for="available_type" class="block">Available For Sale/For Rent:</label>
                <select name="available_type" class="w-full px-3 py-2 border rounded-lg">
                    <option selected value>Select</option>
                    <option value="sale" :selected="'sale' == selectedAvailableType ? 'selected' : null">For Sale</option>
                    <option value="rent" :selected="'rent' == selectedAvailableType ? 'selected' : null">For Rent</option>
                </select>
            </div>
            <div class="mb-8 flex justify-between items-center">
                <button @click="clearForm" type="button" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-200">
                    Clear All
                </button>
                <button type="submit" id="{property.uuid}" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 focus:outline-none focus:ring focus:ring-green-200">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
    </aside>
    <div class="lg:w-3/4 w-full">
        <div v-for="property in properties.data" :key="property.uuid" class="bg-gray-100 lg:py-12 lg:flex lg:justify-center lg:flex-grow">
            <div class="bg-white lg:mx-8 lg:flex lg:max-w-5xl lg:shadow-lg lg:rounded-lg" style="width: 1000px; height: 400px;">
                <!-- Set the fixed width here, for example, 500px -->
                <div class="lg:w-1/2">
                    <div
                    class="h-64 bg-cover lg:rounded-lg lg:h-full"
                    :style="{ backgroundImage: `url('${property.image_thumbnail}')` }"
                    ></div>
                </div>
                <div class="py-2 px-6 max-w-xl lg:max-w-5xl lg:w-1/2 flex flex-col">
                    <p class="mt-4 text-gray-600">{{ property.description }}</p>
                    <div class="mt-8 flex flex-col">
                        <div class="flex items-center px-6 py-1 whitespace-nowrap">
                            <span class="font-semibold">Price:</span>&nbsp;{{ property.price.toLocaleString() }}
                        </div>
                        <div class="flex items-center px-6 py-1 whitespace-nowrap">
                            <span class="font-semibold">Available For {{ property.type }}</span>
                        </div>
                        <div class="flex items-center px-6 py-1 whitespace-nowrap">
                            <span class="font-semibold">Property Type:</span>&nbsp;{{ property.property_type_name }}
                        </div>
                        <div class="flex items-center px-6 py-1 whitespace-nowrap">
                            <span class="font-semibold">Number of bedrooms:</span>&nbsp;{{ property.num_bedrooms }}
                        </div>
                        <div class="flex items-center px-6 py-1 whitespace-nowrap">
                            <span class="font-semibold">Number of bathrooms:</span>&nbsp;{{ property.num_bathrooms }}
                        </div>
                        <div class="flex items-center px-6 py-1 whitespace-nowrap">
                        <button :id="property.uuid" @click="destroy(property.uuid)" class="w-32 px-4 py-2 bg-red-500 text-white rounded-lg">
                            Delete
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Pagination class="mt-6" :links="properties.meta.links" />
    </div>
</div>
</template>
