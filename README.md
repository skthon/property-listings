# Improvements
- Fix redirect for delete property listings action  with success message
- Fix UI styling issues
- Backend
  - Optimize the command class by moving the inertProperties and insertPropertyType to service classes.
  - Add progress bar to the command to know the import status
  - Explore csrf in laravel inertia
  - Explore if the current table indexes are sufficent or check if there is a need to create composite indexes
  - Use cdn to store/serve property images