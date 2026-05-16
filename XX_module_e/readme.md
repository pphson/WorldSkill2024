# Photos Slideshow - User Manual

A browser-based photo slideshow app with manual, auto-play, and random playback modes, plus six visual themes and an integrated command bar.

## 1. What This App Does

- Load photos from your device by drag-and-drop or file picker.
- Optionally load built-in sample photos.
- Reorder loaded photos by dragging preview items.
- Start a slideshow with one of three playback modes.
- Switch visual themes (A to F) at any time.
- Enter fullscreen slideshow view.
- Use a command bar for quick keyboard-driven mode/theme changes.

## 2. Getting Started

1. Open `index.html` in a modern desktop browser.
2. In **Load photos**, do one of the following:
- Drag image files into the drop area.
- Click the drop area and select image files from your system.
- Click **Load Sample Photos**.
3. Confirm at least 4 photos are loaded in the preview list.
4. Choose a slideshow mode and theme in **Configuration**.
5. Click **Start Slideshow**.

## 3. Main Interface

The page has three main sections:

- **Load photos**
- Drop zone and file input for image loading.
- Buttons: **Load Sample Photos**, **Clear**.

- **Configuration**
- Preview list of loaded photos.
- Drag-and-drop sorting of preview items.
- Slideshow mode selector: manual, auto, random.
- Theme selector: A, B, C, D, E, F.
- Start button.

- **Slideshow**
- Main slideshow display area.
- Fullscreen button and exit-fullscreen button.

## 4. Loading Photos

### Drag-and-drop

- Drag one or more image files into the drop zone.
- Non-image files are ignored.

### File picker

- Click the drop zone to open the file dialog.
- Select one or more images.

### Sample photos

- Click **Load Sample Photos** to insert built-in sample images.

### Captions

Photo captions are generated from file names:

- File extension is removed.
- Slug/word separators are normalized.
- Words are title-cased.

Examples:

- `hello.jpg` -> `Hello`
- `hello world.jpg` -> `Hello World`
- `a-sample-photo.jpg` -> `A Sample Photo`

## 5. Photo List and Sorting

- All loaded photos appear in the preview list.
- Drag preview items to reorder slideshow order.
- The slideshow uses the current preview order when started.

## 6. Slideshow Rules

- Minimum required photos: **4**.
- If fewer than 4 photos are loaded:
- Start button is disabled.
- Mode/theme controls are disabled.
- Command bar is disabled.

## 7. Playback Modes

### Manual control

- Use keyboard arrows while slideshow is active:
- Right arrow: next photo.
- Left arrow: previous photo.

### Auto play

- Slides advance automatically at a fixed interval.
- Current interval: approximately 2 seconds per slide.
- Loops back to the first slide after the last one.

### Random

- Slides advance automatically.
- Next slide is selected randomly.
- Runs continuously (non-terminating playback behavior).

## 8. Themes

### Theme A

- Basic display with no transition effects.

### Theme B

- Horizontal motion effect.
- Caption follows with delay.

### Theme C

- Vertical motion effect.
- Caption words animate with staggered timing.

### Theme D

- Stacked photo look.
- Slight random rotation per photo.
- White frame + caption strip style.

### Theme E

- Split-door opening style transition.
- Captions hidden.

### Theme F

- Custom transition style with animated mask-based reveal.
- Caption reveal animation included.

## 9. Command Bar

Open the command bar with:

- `Ctrl+K` (Windows/Linux)
- `Cmd+K` (Mac)
- `/`

Close/cancel with:

- `Esc`
- Cancel button
- Close icon

How to use:

1. Type to filter available commands.
2. Use Up/Down arrows to move through visible options.
3. Press Enter or click **Execute** to run the selected command.

Supported commands:

- `mode manual`
- `mode auto`
- `mode random`
- `theme A`
- `theme B`
- `theme C`
- `theme D`
- `theme E`
- `theme F`

## 10. Fullscreen

- Click expand icon on the slideshow card to enter fullscreen.
- Click compress icon or use browser/system fullscreen exit shortcut to leave.

## 11. Clear All Photos

- Click **Clear**.
- Confirm in the dialog.
- All preview items and slideshow items are removed.

## 12. Browser Notes

- Best used in current versions of Chrome, Edge, and Firefox.
- JavaScript must be enabled.
- If CSS is unavailable, file input remains usable for loading images.

## 13. Quick Workflow (Recommended)

1. Load sample photos to test quickly.
2. Reorder photos in preview.
3. Choose mode and theme.
4. Start slideshow.
5. Toggle fullscreen for presentation.
6. Use command bar for fast mode/theme switching.
