import { usePage } from "@inertiajs/vue3";

const page = usePage();

export function getUserID() {
    return page.props.user?.id;
}

export function getUserRole() {
    return page.props.user?.role;
}
