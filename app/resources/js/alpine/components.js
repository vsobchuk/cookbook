export const toggleFavorite = function () {
    return {
        id: null,
        checked: false,
        init() {
            this.id = this.$el.dataset.id;
            this.checked = !!parseInt(this.$el.dataset.checked || '0');
        },
        onClick() {
            const endpoint = `/meals/${this.id}/favorite`;

            if (! this.checked) {
                axios.post(endpoint).then(() => this.checked = true);
            } else {
                axios.delete(endpoint).then(() => this.checked = false);
            }
        },
    }
}
