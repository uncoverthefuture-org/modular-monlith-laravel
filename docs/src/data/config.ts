import introDoc from "./intro.md?raw";
import installDoc from "./installation.md?raw";
import quickstartDoc from "./quickstart.md?raw";
import configDoc from "./configuration.md?raw";
import baseClassesDoc from "./base-classes.md?raw";
import apiResponseDoc from "./api-response.md?raw";

export interface UIConfig {
  siteTitle?: string;
  favicon?: string;
  sidebarFrameUrl?: string;
  docContentFrameUrl?: string;
  defaultTheme?: "light" | "dark";
  showMobileMenu?: boolean;
}

export const UI_CONFIG: UIConfig = {
  siteTitle: "Modular Monolith Laravel",
  favicon: "/uncoverthefuture.svg",
  sidebarFrameUrl: "frames/sidebar",
  docContentFrameUrl: "frames/doc_content",
  defaultTheme: "dark",
  showMobileMenu: true,
};

export interface NavItem {
    label: string;
    route?: string;
    children?: NavItem[];
    external?: string;
}

export const navItems: NavItem[] = [
    {
        label: "Getting Started",
        children: [
            {
                label: "Introduction",
                route: "/"
            },
            {
                label: "Installation",
                route: "/installation"
            },
            {
                label: "Quick Start",
                route: "/quickstart"
            },
            {
                label: "Configuration",
                route: "/configuration"
            },
        ],
    },
    {
        label: "Reference",
        children: [
            {
                label: "Base Classes",
                route: "/base-classes"
            },
            {
                label: "API Response",
                route: "/api-response"
            },
        ],
    },
    {
        label: "About",
        children: [
            {
                label: "Uncover Actions",
                external: "https://uncoverthefuture-org.github.io/uactions/",
            },
            {
                label: "GitHub Repository",
                external: "https://github.com/uncoverthefuture-org/modular-monlith-laravel",
            },
        ],
    },
];

export const titleMap: Record<string, string> = {
    "/": "Modular Monolith Laravel",
    "/installation": "Installation",
    "/quickstart": "Quick Start",
    "/configuration": "Configuration",
    "/base-classes": "Base Classes",
    "/api-response": "API Response",
};

export const contentMap: Record<string, string> = {
    "/": introDoc,
    "/installation": installDoc,
    "/quickstart": quickstartDoc,
    "/configuration": configDoc,
    "/base-classes": baseClassesDoc,
    "/api-response": apiResponseDoc,
};
