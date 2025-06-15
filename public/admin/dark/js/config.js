"use strict";

// Base configuration
const base = {
    defaultFontFamily: "Overpass, sans-serif",
    primaryColor: "#1b68ff",
    secondaryColor: "#4f4f4f",
    successColor: "#3ad29f",
    warningColor: "#ffc107",
    infoColor: "#17a2b8",
    dangerColor: "#dc3545",
    darkColor: "#343a40",
    lightColor: "#f2f3f6"
};

// Extended colors
const extend = {
    primaryColorLight: tinycolor(base.primaryColor).lighten(10).toString(),
    primaryColorLighter: tinycolor(base.primaryColor).lighten(30).toString(),
    primaryColorDark: tinycolor(base.primaryColor).darken(10).toString(),
    primaryColorDarker: tinycolor(base.primaryColor).darken(30).toString()
};

// Chart colors
const chartColors = [
    base.primaryColor,
    base.successColor,
    "#6f42c1",
    extend.primaryColorLighter
];

// Theme colors
const colors = {
    bodyColor: "#6c757d",
    headingColor: "#495057",
    borderColor: "#e9ecef",
    backgroundColor: "#f8f9fa",
    mutedColor: "#adb5bd",
    chartTheme: "light"
};

// Apply theme colors
document.documentElement.style.setProperty('--primary', base.primaryColor);
document.documentElement.style.setProperty('--secondary', base.secondaryColor);
document.documentElement.style.setProperty('--success', base.successColor);
document.documentElement.style.setProperty('--info', base.infoColor);
document.documentElement.style.setProperty('--warning', base.warningColor);
document.documentElement.style.setProperty('--danger', base.dangerColor);
document.documentElement.style.setProperty('--dark', base.darkColor);
document.documentElement.style.setProperty('--light', base.lightColor);
document.documentElement.style.setProperty('--muted', colors.mutedColor);
document.documentElement.style.setProperty('--body-bg', colors.backgroundColor);
document.documentElement.style.setProperty('--body-color', colors.bodyColor);
document.documentElement.style.setProperty('--border-color', colors.borderColor);